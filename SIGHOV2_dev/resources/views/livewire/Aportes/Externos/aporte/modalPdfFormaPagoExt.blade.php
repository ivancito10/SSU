<div>
    <x-ver-modal id="formaPagoExt" labelledby="formaPagoExtLabel"  title=" ARCHIVO FILE -  COMPROBANTE DE PAGO ">
        
        @if($pdf_forma_pago_ext)
            <iframe src="data:application/pdf;base64,{{ $this->pdf_forma_pago_ext }}#toolbar=0"
            width="100%" height="600px" ></iframe>
        @else
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                <h5> <em style="color: #c0392b;"> No se encontró el comprobante de pago. </em></h5>
            </div>
        </div>
            
        @endif 
    <!--
    #toolbar=0
    si quito esta extension me sale las opciones del pdf...-->
    </x-ver-modal>
</div>
<script>
    window.addEventListener('modalFormaPagoExt', event => {
        $('#formaPagoExt').modal('show');

        $('#verFormaPagoExt').css('overflow-y', 'auto');
    });

</script>

