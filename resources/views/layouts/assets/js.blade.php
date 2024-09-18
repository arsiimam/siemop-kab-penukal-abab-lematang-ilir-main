<script src="{{ asset('js/vendor/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/vendor/perfect-scrollbar.min.js') }}"></script>

<script src="{{ asset('js/vendor/Chart.bundle.min.js') }}"></script>
<script src="{{ asset('js/vendor/chartjs-plugin-datalabels.js') }}"></script>
<script src="{{ asset('js/vendor/moment.min.js') }}"></script>
<script src="{{ asset('js/vendor/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
<script src="{{ asset('js/vendor/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('js/vendor/ckeditor5-build-classic/ckeditor.js') }}"></script>
<script src="{{ asset('js/vendor/progressbar.min.js') }}"></script>
<script src="{{ asset('js/vendor/jquery.barrating.min.js') }}"></script>
<script src="{{ asset('js/vendor/select2.full.js') }}"></script>
<script src="{{ asset('js/vendor/nouislider.min.js') }}"></script>
<script src="{{ asset('js/vendor/dropzone.min.js') }}"></script>
<script src="{{ asset('js/vendor/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('js/vendor/Sortable.js') }}"></script>

<script src="{{ asset('js/vendor/mousetrap.min.js') }}"></script>
<script src="{{ asset('js/vendor/glide.min.js') }}"></script>
<script src="{{ asset('js/dore.script.js') }}"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
<script src="{{ asset('vendor/notice/notice.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('vendor') }}/daterangepicker/daterangepicker.min.js"></script>

<script src="{{ asset('vendor') }}/sweetalert/sweetalert2.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"
    integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $('.multi_date').daterangepicker()

    $(document).on("click", ".btn-delete", function(e) {
        e.preventDefault();
        var form = $(this).parents('form');
        Swal.fire({
            position: 'center',
            title: 'Apakah Anda Yakin?',
            text: "Anda tidak akan dapat mengembalikan data yang sudah dihapus !",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    function submitForm(id) {
        document.getElementById(id).submit();
    }

    function formatNumberField() {
        var value = this.value.replace(/[^\d,]/g, '');
        var matches = /^(?:(\d{1,3})?((?:\d{3})*))((?:,\d*)?)$/.exec(value);

        if (!matches) {
            return;
        }

        var spaceified = matches[2].replace(/(\d{3})/g, '.$1');
        this.value = [matches[1], spaceified, matches[3]].join('');
    }

    $(document).on("click", ".modal-btn", function() {
        var url = $(this).data('url');
        var title = $(this).data('title');
        $.ajax({
            url: url,
            type: 'get',
            success: function(response) {
                if (response.status === 'error') {
                    $.toast({
                        heading: 'Error',
                        text: response.message,
                        icon: 'error',
                        position: 'top-right',
                        hideAfter: 3000,
                        showHideTransition: 'slide'
                    })
                } else {

                    $('.modal-title-load').text(title);
                    $('.modal-body-load').html(response);
                    $('#empModal').modal({
                        show: true
                    });

                    $('.select2-modal').select2({
                        theme: "bootstrap",
                        maximumSelectionSize: 6,
                        containerCssClass: ":all:",
                        placeholder: "None / Select",
                        allowClear: true
                    });
                }
            },
            error: function(response) {
                $.toast({
                    heading: 'Error',
                    text: response.responseJSON.error,
                    icon: 'error',
                    position: 'top-right',
                    hideAfter: 3000,
                    showHideTransition: 'slide'
                })
            }

        });
    });

    $(document).on("click", ".modal-btn-xl", function() {
        var url = $(this).data('url');
        var title = $(this).data('title');
        $.ajax({
            url: url,
            type: 'get',
            success: function(response) {
                if (response.status === 'error') {
                    $.toast({
                        heading: 'Error',
                        text: response.message,
                        icon: 'error',
                        position: 'top-right',
                        hideAfter: 3000,
                        showHideTransition: 'slide'
                    })
                } else {
                    $('.modal-title-load').text(title);
                    $('.modal-body-load').html(response);
                    $('#empModal-xl').modal({
                        show: true
                    });

                    $('.select2-modal').select2({
                        theme: "bootstrap",
                        maximumSelectionSize: 6,
                        containerCssClass: ":all:",
                        placeholder: "None / Select",
                        allowClear: true
                    });

                    $('.datatable-modal').dataTable({
                        sDom: '<"row view-filter"<"col-sm-12"<"float-left"l><"float-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>'
                    });
                }
            },
            error: function(response) {
                $.toast({
                    heading: 'Error',
                    text: response.responseJSON.error,
                    icon: 'error',
                    position: 'top-right',
                    hideAfter: 3000,
                    showHideTransition: 'slide'
                })
            }

        });
    });

    // select 2
    $('.select2-with-clear').select2({
        theme: "bootstrap",
        maximumSelectionSize: 6,
        containerCssClass: ":all:",
        placeholder: "None / Select",
        allowClear: true
    });

    // dropzone
    $(".dropzone_single").dropzone({
        maxFiles: 1,
        maxFilesize: 5,
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        url: "{{ url('postimage') }}",
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {
            this.on("maxfilesexceeded", function(file) {
                this.removeAllFiles();
                this.addFile(file);
            });

            this.on("success", function(file, responseText) {
                console.log(responseText);
                if (responseText.status) {
                    $('#image_encode').val(responseText.file)
                }
            });
        },
        thumbnailWidth: 160,
        previewTemplate: '<div class="dz-preview dz-file-preview mb-3"><div class="d-flex flex-row "><div class="p-0 w-30 position-relative"><div class="dz-error-mark"><span><i></i></span></div><div class="dz-success-mark"><span><i></i></span></div><div class="preview-container"><img data-dz-thumbnail class="img-thumbnail border-0" /><i class="simple-icon-doc preview-icon" ></i></div></div><div class="pl-3 pt-2 pr-2 pb-1 w-70 dz-details position-relative"><div><span data-dz-name></span></div><div class="text-primary text-extra-small" data-dz-size /><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div><div class="dz-error-message"><span data-dz-errormessage></span></div></div></div><a href="#/" class="remove" onclick="remove_pic();" data-dz-remove><i class="glyph-icon simple-icon-trash"></i></a></div>'
    });

    var wrapper = $("#images-recent");
    $(".dropzone_multiple").dropzone({
        maxFilesize: 5,
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        url: "{{ url('postimage') }}",
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {

            this.on("success", function(file, responseText) {
                console.log(responseText);
                $(wrapper).append(
                    `<ul class="gallery margin-top-10">
                            <li class="profile">
                                <img src="` + responseText.file + `" />
                                <div class="alt_image">
                                    <p class="alt_caption">
                                        <span class="alt_title">` + responseText.title + `</span>
                                        <br>
                                        <span>` + responseText.size + `</span>
                                    </p>
                                </div>
                                <button type="button" class="btn-link remove_field"><i class="simple-icon-trash"></i></button>
                                <input type="hidden" class="form-control" name="caption[]" value="` + responseText
                    .title + `" readonly >
                                <textarea class="form-control" name="gallery[]" readonly style="display: none">` +
                    responseText.file + `</textarea>
                            </li>
                        </ul>`
                )
                if (this.getQueuedFiles().length == 0 && this.getUploadingFiles()
                    .length == 0) {
                    var _this = this;
                    _this.removeAllFiles();
                }
            });
        },
        thumbnailWidth: 160,
        previewTemplate: '<div class="dz-preview dz-file-preview mb-3"><div class="d-flex flex-row "><div class="p-0 w-30 position-relative"><div class="dz-error-mark"><span><i></i></span></div><div class="dz-success-mark"><span><i></i></span></div><div class="preview-container"><img data-dz-thumbnail class="img-thumbnail border-0" /><i class="simple-icon-doc preview-icon" ></i></div></div><div class="pl-3 pt-2 pr-2 pb-1 w-70 dz-details position-relative"><div><span data-dz-name></span></div><div class="text-primary text-extra-small" data-dz-size /><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div><div class="dz-error-message"><span data-dz-errormessage></span></div></div></div><a href="#/" class="remove" data-dz-remove><i class="glyph-icon simple-icon-trash"></i></a></div>'
    });

    function remove_pic() {
        $("#image_encode").val('');
        console.log('dihapus');
    }

    $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
        e.preventDefault();
        $(this).parents('.gallery').remove();
    })

    function goback() {
        window.history.back();
    }

    const notice = new Notice();

    function notify_payload(heading, text, icon) {
        $.toast({
            heading: heading,
            text: text,
            icon: icon,
            position: 'top-right',
            hideAfter: 4000,
            showHideTransition: 'slide',
        })
    }
</script>

<script>
    var CKEditorArray = []; //CKEditor access array

    ClassicEditor

        .create(document.querySelector('#editor'), {
            fontSize: {
                options: [
                    9,
                    11,
                    13,
                    'default',
                    17,
                    19,
                    21
                ]
            },
            removePlugins: ['CKFinderUploadAdapter', 'CKFinder', 'EasyImage', 'Image', 'ImageCaption', 'ImageStyle',
                'ImageToolbar', 'ImageUpload', 'MediaEmbed'
            ],
        })
        .then(editor => {
            editor.ui.view.editable.element.style.height = '278px';
        })
        .catch(function(error) {});
</script>
