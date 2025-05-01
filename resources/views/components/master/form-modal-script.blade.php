<script type="module">
    let maps={};
    let editors={};
    document.getElementById('modalForm').addEventListener('shown.bs.modal', function () {
        document.querySelectorAll('.leaflet-map').forEach((mapElement) => {
            const mapId = mapElement.id;
            const mapField = mapElement.dataset.field;
            const mapValue = document.getElementById(mapField).value;
            let drawnItems = new L.FeatureGroup();
            let geoJsonString;

            if (maps[mapId]) {
                maps[mapId].remove();
                delete maps[mapId];
            }

            maps[mapId] = L.map(mapId).setView([-7.0043, 114.0493], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(maps[mapId]);

            maps[mapId].addLayer(drawnItems);

            if(mapValue){
                let mapValueJson = JSON.parse(mapValue);
                let geoJsonData = null;
                if (mapValueJson.type.startsWith("Multi")) {
                    let arrFeature = [];
                    mapValueJson.coordinates.forEach(coordinate =>
                        arrFeature.push({
                            "type": "Feature",
                            "properties": {},
                            "geometry": {
                                "type": mapValueJson.type.replace(/^Multi/, ''),
                                "coordinates": coordinate
                            }
                    }));
                    geoJsonData = {
                        "type": "FeatureCollection",
                        "features": arrFeature
                    };
                }else if(mapValueJson.type === "GeometryCollection") {
                    let arrFeature = [];
                    mapValueJson.geometries.forEach(geometry =>
                        arrFeature.push({
                            "type": "Feature",
                            "properties": {},
                            "geometry": geometry
                    }));
                    geoJsonData = {
                        "type": "FeatureCollection",
                        "features": arrFeature
                    };
                }else{
                    geoJsonData = {
                        "type": "Feature",
                        "properties": {},
                        "geometry": JSON.parse(mapValue)
                    };
                }

                L.geoJSON(geoJsonData, {
                    onEachFeature: function (feature, layer) {
                        drawnItems.addLayer(layer);
                    },
                });

                maps[mapId].fitBounds(L.geoJSON(geoJsonData).getBounds());
            }

            let drawControl = new L.Control.Draw({
                edit: {
                    featureGroup: drawnItems,
                },
                draw: {
                    polyline: true,
                    circle: false,
                    rectangle: false,
                    marker: true,
                    polygon: true,
                },
            });
            maps[mapId].addControl(drawControl);

            function geoJsonToWKT(geoJson) {
                const { type, coordinates } = geoJson;

                if (type === 'Point') {
                    return `POINT(${coordinates.join(' ')})`;
                } else if (type === 'Polygon') {
                    const rings = coordinates
                        .map(ring => `(${ring.map(coord => coord.join(' ')).join(', ')})`)
                        .join(', ');
                    return `POLYGON(${rings})`;
                } else if (type === 'LineString') {
                    const line = coordinates.map(coord => coord.join(' ')).join(', ');
                    return `LINESTRING(${line})`;
                }
                throw new Error('Unsupported geometry type');
            }

            maps[mapId].on(L.Draw.Event.CREATED, function (e) {
                let allGeometries = [];
                let layer = e.layer;

                drawnItems.addLayer(layer);
                drawnItems.eachLayer(function (layer) {
                    let geoJson = layer.toGeoJSON();
                    allGeometries.push(geoJson);
                });

                let geoJsonCollection = {
                    type: "FeatureCollection",
                    features: allGeometries,
                };
                geoJsonString = JSON.stringify(geoJsonCollection);
                Livewire.dispatch('updateInputMap', [mapField, geoJsonString]);
            });
        });
        document.querySelectorAll('.editor-ckeditor5').forEach((element) => {
            const editorField = element.dataset.field;

            if (editors[editorField]){
                editors[editorField].destroy();
                element.textContent = null;
            }
            editorConfig.initialData = document.getElementById(editorField).innerHTML;
            ClassicEditor.create(element, editorConfig)
            .then(editor => {
                editor.model.document.on('change:data', () => {console.log(editor.getData());console.log(editor.data.stringify(editor.model.document.getRoot()));
                    Livewire.dispatch('updateInputData', [editorField, editor.getData()]);
                });
                editors[editorField] = editor;
            })
            .catch(error => {
                console.error(error);
            });
        });

        document.querySelectorAll('.select2').forEach((element) => {
            const $element = $(element);
            const selectField = element.name;
            $element.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Select value',
                dropdownParent: $element.parent()
            })
            .on('change', function (e) {
                let value = $element.val();
                Livewire.dispatch('updateInputData', [selectField, value]);
            });
        });
    });
    $(document).on('click', '[data-kt-action="delete_file"]', function() {
        const dataId = $(this).attr('data-id');

        Swal.fire({
            text: 'Apa anda yakin akan menghapus File ini?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteFile', [dataId]);
            }
        });
    });

    document.addEventListener('livewire:init', function() {
        Livewire.on('success', msg => {
            $('#modalForm').modal('hide');
            Swal.fire({
                position: 'middle',
                icon: 'success',
                title: msg,
                showConfirmButton: false,
                timer: 1500,
                customClass: {
                confirmButton: 'btn btn-primary waves-effect waves-light'
                },
                buttonsStyling: false
            });
            datatable.ajax.reload();
        });
    });

    $( '#modalForm' ).modal( {
        focus: false
    } );
</script>
