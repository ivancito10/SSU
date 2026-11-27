<div>
    <x-ver-modal id="modalPdfFormaPago" labelledby="modalPdfFormaPagoLabel"  title=" DEPÓSITO GUARDADO PDF ">
        
        @if($pdf_forma_pago)
            <iframe src="data:application/pdf;base64,{{ $this->pdf_forma_pago }}#toolbar=0"
            width="100%" height="600px" ></iframe>
        @else
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                <h5> <em style="color: #c0392b;"> No se encontró el pdf del depósito. </em></h5>
            </div>
        </div>
            
        @endif 
    <!--
    #toolbar=0
    si quito esta extension me sale las opciones del pdf...-->
    </x-ver-modal>
</div>
<script>
    window.addEventListener('modalVerPdFormaPago', event => {
        $('#modalPdfFormaPago').modal('show');
        $('#liquidacionModal').css('overflow-y', 'auto');
    });

</script>

