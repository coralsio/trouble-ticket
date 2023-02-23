@push('partial_js')
    @guest
        {!! NoCaptcha::renderJs() !!}
    @endguest
    <script>
        $('#auto_assignment').on('change', function () {
            showHideElement('#auto_assignment', '#assignee_id');
        });

        $('#is_public_owner').on('change', function () {
            showHideElement('#is_public_owner', '#owner_id', showHidePublicOwnerDetails);
        });


        function showHidePublicOwnerDetails(isPublicOwnerChecked) {
            if (isPublicOwnerChecked) {
                $("[name='public_owner[email]']").closest('.toggle-wrapper').fadeIn();
                $("[name='public_owner[name]']").closest('.toggle-wrapper').fadeIn();
            } else {
                $("[name='public_owner[email]']").closest('.toggle-wrapper').hide();
                $("[name='public_owner[name]']").closest('.toggle-wrapper').hide();
            }
        }


        function showHideElement(checkBoxElement, elementId, showHideCallback = null) {

            let isChecked = $(checkBoxElement).is(':checked');

            if (isChecked) {
                $(elementId).closest('.toggle-wrapper').hide();

            } else {
                $(elementId).closest('.toggle-wrapper').fadeIn();
            }

            if (showHideCallback) {
                showHideCallback(isChecked);
            }
        }

        showHideElement('#auto_assignment', '#assignee_id');
        showHideElement('#is_public_owner', '#owner_id', showHidePublicOwnerDetails);


        let parents = @json(get_models('trouble_ticket.models.troubleTicket.model_field_models'));

        updateParentObjectField(parents, $("#model_type"), $("#model_id"));

        $('#category_id').trigger('change');
    </script>
@endpush
