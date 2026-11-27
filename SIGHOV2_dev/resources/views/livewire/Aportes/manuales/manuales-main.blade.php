<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:8px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="20 " fill="white" class="bi bi-folder-fill"
                viewBox="0 0 16 16">
                <path
                    d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3m-8.322.12q.322-.119.684-.12h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981z" />
            </svg>
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">MANUALES DE USUARIO
            </h7>
        </div>
        
    </div>
    <br>
    @can('menu.manual_aportes_planillaFormato')
    <div class="row"></div>
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="card h-100 d-flex flex-column justify-content-between pr-0 pl-0 pt-4 pb-2"
                style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); background-color:#799bb7"
                wire:click="manualLlenadoExcel">
                <div class="d-flex justify-content-center">
                    <img src="/images/portada_planilla_excel.png" class="img-thumbnail" width="200" height="200"
                        style="border-radius:10px">
                </div>
                <h3 style="text-align:center"> <b>
                      LLENADO DE PLANILLA  EN EXCEL
                    </b></h3>
            </div>

        </div>
            @include('livewire.Aportes.manuales.ManualPlanillaExcel')

        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
    </div>
    
    @endcan
</div>
