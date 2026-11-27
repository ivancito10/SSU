<x-ver-modal id="modalInstructivo" labelledby="modalInstructivoLabel" title="INSTRUCTIVO ASUSS 2025">
    <div style="height: 80vh;">
        <iframe src="{{ asset('docs/aportes/instructivo_asuss.pdf') }}" 
                width="100%" 
                height="100%" 
                style="border: none; border-radius: 10px;">
        </iframe>
    </div>
</x-ver-modal>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var instructivoModal = new bootstrap.Modal(document.getElementById('modalInstructivo'));
        instructivoModal.show();
    });
</script>