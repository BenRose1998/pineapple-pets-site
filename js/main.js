$(document).ready(function(){
  console.log('jQuery Running');

  // current page
  var location = window.location.pathname.split('/')[2] + window.location.search;
  console.log(location);
  
  // for each nav link
  $('ul li a').each(function(){
    // nav link's href attribute
    var href = $(this).attr('href');
    // check if url pathname matches link's href
    if(href.indexOf(location) !== -1){
      console.log('matching');
      
      // add active class to nav link
      $(this).addClass('active');
    }
  });

  // Edit Form

  // Add option
  $('button.add-option').click(function(e){
    e.preventDefault();
    console.log($(this).parents());
    
    var question_id = $(this).parents()[1].id;

    var answers_div = $('div#' + question_id +'.answers');
    
    var id = $.now();

    var html = '<div class="col-md-3"></div>' +
               '<div class="form-group col-md-9 question-text">' +
               '<label class="option-input">Option: </label>' +
               '<input type="hidden" name="answers[' + id + '][question_id]" value="' + question_id + '">' +
               '<input type="text" class="form-control col-md-9 option-input" name="answers[' + id + '][answer]">' +
               '<a href=""><button type="button" class="btn-block col-md-1 add-option">X</button></a>' +
               '</div>';

    answers_div.append(html);
  });

  // Question
  $('button.add-question').click(function(e){
    e.preventDefault();
    
    console.log($(this).parents());
    var form = $(this).parents()[2];

    var question_div = $(this).parents()[1];
    var question_id = question_div.id;

    var category_id = $('input[name="questions[' + question_id + '][category_id]"]').val();
    
    var id = $.now();

    var input_type_html = '<hr>' +
               '<div class="question" id="">' +
               '<div class="row">' +
               '<div class="form-group col-md-3 input-type">' +
               '<label>Input type: </label>' +
               '<select class="form-control" name="questions[' + id + '][input_type]" value="input">' +
               '<option value="input" selected>Text</option>' +
               '<option value="dropdown">Dropdown</option>' +
               '<option value="check">Checkboxes</option>' +
               '<option value="text">Big text area</option>' +
               '</select>' +
               '</div>';

    var question_html = '<div class="form-group col-md-9 question-text">' +
                        '<label>Question Title: </label>' +
                        '<input type="hidden" name="questions[' + id + '][category_id]" value="' + category_id + '">' +
                        '<input type="text" class="form-control" name="questions[' + id + '][text]" value="">' +
                        '</div>' +
                        '</div>';

    // var question_html = '<div class="form-group col-md-9 question-text">' +
    //                     '<label>Question Title: </label>' +
    //                     '<input type="hidden" name="questions[' + id + '][category_id]" value="' + category_id + '">' +
    //                     '<input type="text" class="form-control" name="questions[' + id + '][text]" value="">' +
    //                     '</div>' +
    //                     '</div>' +
    //                     '<a href=""><button type="button" class="btn-block add-question">Add New Question Here</button></a>' +
    //                     '</div>';

    $(question_div).after(input_type_html + question_html);

  });

  $('.input-type select').change(function(e){
    console.log('changed');
    $('form').submit();
    
  });


});



  