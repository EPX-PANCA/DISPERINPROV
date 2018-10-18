jQuery(document).ready(function ($) {

    if( $('.awpa-post-author-type option[selected="selected"]').val() == 'specific-author' ){
        $('.awpa-post-author-type').parents('.widget-content').find('.ac-awpa-post-author-type').show();
    }else{
        $('.awpa-post-author-type').parents('.widget-content').find('.ac-awpa-post-author-type').hide();
    }

	$('.awpa-post-author-type').on('change', function(){
        
        if( $(this).val() == 'specific-author' ){
            $(this).parents('.widget-content').find('.ac-awpa-post-author-type').show();
        }else{
            $(this).parents('.widget-content').find('.ac-awpa-post-author-type').hide();

        }

	});
});