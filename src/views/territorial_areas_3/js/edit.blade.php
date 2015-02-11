<script type="text/javascript"> 
    function getAreasTerritoriales2FromAreaTerritorial1(id){
        $.ajax({
            type: "POST",
            url: "{{ URL::to(Config::get('pulsar::pulsar.rootUri')) }}/pulsar/areasterritoriales2/json/get_areas_territoriales_2_from_area_territorial_1/"+id,
            dataType: 'json',
            success: function(data) {
                $("[name='areaTerritorial2'] option").remove();
                $("[name='areaTerritorial2']").append(new Option('Elija un/a <?php echo $pais->area_territorial_2_002; ?>', 'null'));
                for(var i in data)
                {
                    $("[name='areaTerritorial2']").append(new Option(data[i].nombre_004, data[i].id_004));
                }
            }
        });
    }
    
    $(document).ready(function() {
        $("[name='areaTerritorial1']").change(
            function(){
                getAreasTerritoriales2FromAreaTerritorial1($("[name='areaTerritorial1']").val());
            }
        );
    });
</script>
