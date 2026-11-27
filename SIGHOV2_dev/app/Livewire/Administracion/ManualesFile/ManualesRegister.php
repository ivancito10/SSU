<?php

namespace App\Livewire\Administracion\ManualesFile;

use App\Models\Administracion\Manual;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManualesRegister extends Component
{
    use WithFileUploads;

    public string $modulo = '';
    public string $titulo = '';
    public string $version = '1.0';
    public string $descripcion = '';
    public $archivo_pdf = null;

    public function render()
    {
        return view('livewire.Administracion.manuales-file.manuales-register', [
            'modulos' => Manual::MODULOS,
        ]);
    }

    protected function rules(): array
    {
        return [
            'modulo'      => 'required|string|max:100',
            'titulo'      => 'required|string|min:3|max:180',
            'version'     => 'required|string|max:30',
            'descripcion' => 'nullable|string|max:1000',
            'archivo_pdf' => 'required|file|mimes:pdf|max:20480',
        ];
    }

    protected function messages(): array
    {
        return [
            'modulo.required'      => 'Debe seleccionar el módulo del sistema.',
            'titulo.required'      => 'El título del manual es obligatorio.',
            'titulo.min'           => 'El título debe tener al menos 3 caracteres.',
            'version.required'     => 'La versión es obligatoria (ej. 1.0).',
            'archivo_pdf.required' => 'Debe seleccionar un archivo PDF.',
            'archivo_pdf.mimes'    => 'Solo se permiten archivos en formato PDF.',
            'archivo_pdf.max'      => 'El archivo PDF no debe superar los 20 MB.',
        ];
    }

    #[On('guardar_manual')]
    public function guardar_manual(): void
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $primerError = collect($e->errors())->first()[0] ?? 'Complete los campos obligatorios marcados en rojo.';
            $this->dispatch('alertaErrorManual', mensaje: $primerError);
            throw $e;
        }

        $rutaSubida = null;

        try {
            DB::transaction(function () use (&$rutaSubida) {
                $idUser = auth()->id();

                // 1. Insertar primero para obtener el ID real y único
                $manual = Manual::create([
                    'modulo'          => mb_strtoupper(trim($this->modulo)),
                    'titulo'          => mb_strtoupper(trim($this->titulo)),
                    'version'         => trim($this->version) ?: '1.0',
                    'descripcion'     => trim($this->descripcion) !== '' ? trim($this->descripcion) : null,
                    'estado'          => true,
                    'id_user_created' => $idUser,
                ]);

                // 2. Construir nombre y ruta única dentro de FTP
                $slugModulo = Str::slug($manual->modulo, '_');
                $slugTitulo = Str::slug(Str::limit($manual->titulo, 60, ''), '_');
                $nombreArchivo = $manual->id . '_' . ($slugTitulo ?: 'manual') . '.pdf';
                $carpetaFtp = 'Administracion/Manuales/' . $slugModulo;

                // 3. Subir al servidor FTP dentro de la transacción (si falla, DB hace rollback automático)
                $rutaGuardada = Storage::disk('ftp')->putFileAs(
                    $carpetaFtp,
                    $this->archivo_pdf,
                    $nombreArchivo
                );

                if (!$rutaGuardada) {
                    throw new \RuntimeException('No se pudo subir el archivo PDF al servidor FTP.');
                }

                $rutaSubida = $rutaGuardada;

                // 4. Guardar nombre, ruta FTP y tamaño
                $manual->update([
                    'nombre_archivo' => $nombreArchivo,
                    'ruta_ftp'       => $rutaGuardada,
                    'tamano_bytes'   => $this->archivo_pdf->getSize(),
                ]);
            });

            $this->reset(['modulo', 'titulo', 'descripcion', 'archivo_pdf']);
            $this->version = '1.0';
            $this->resetValidation();

            $this->dispatch('pg:eventRefresh-ManualesTable');
            $this->dispatch('cerrarModalRegistrarManual');
            $this->dispatch('guardado_manual');
        } catch (\Throwable $e) {
            if ($rutaSubida) {
                try {
                    Storage::disk('ftp')->delete($rutaSubida);
                } catch (\Throwable $ignore) {
                }
            }

            Log::error('ManualesRegister@guardar_manual error: ' . $e->getMessage());
            $this->dispatch('alertaErrorManual', mensaje: 'Error al subir el manual por FTP: ' . $e->getMessage());
        }
    }

    #[On('cancelar_registro_manual')]
    public function cancelar_registro_manual(): void
    {
        $this->reset(['modulo', 'titulo', 'descripcion', 'archivo_pdf']);
        $this->version = '1.0';
        $this->resetValidation();
        $this->dispatch('cerrarModalRegistrarManual');
    }
}
