$(document).ready(function () {
    $('.check-btn').click(function () {
        console.log($(this).attr('id'))

        $('body').addClass('overflow_hidden')

        let projectId = $(this).attr('id')

        $('.overlay').removeClass('hidden')

        $('.project')
            .fadeIn(1000)
            .html($(`template#${projectId}`).html())



        $('.overlay, .project__exit').click(function () {
            $.when(

                $('.project')
                    .fadeOut('slow')

            ).done(function () {

                $('.overlay').addClass('hidden')
                $('.project').html('')
                $('body').removeClass('overflow_hidden')

            })
        })
    })
})


