// When document has fully loaded run this anonymous function
$(document).ready(function () {
  // console.log('jQuery Running');

  // Store a reference to the current page
  var location = window.location.pathname.split('/')[2] + window.location.search;

  // Loop through all navigation links
  $('ul li a').each(function () {
    // Store this navigation link's href attribute value
    var href = $(this).attr('href');
    // Check if url pathname matches link's href
    if (href.indexOf(location) !== -1) {
      // If matching add active class to the nav link
      $(this).addClass('active');
    }
  });

  // Edit Form - Add option
  // If the add option button is clicked
  $('button.add-option').click(function (event) {
    // Prevent default behaviour of button (redirected user)
    event.preventDefault();
    // Store a reference to the question id this button is related to by taking the id value of it's 2nd parent element
    var question_id = $(this).parents()[1].id;
    // Store a reference to the answers div of the question this button corresponds 
    var answers_div = $('div#' + question_id + '.answers');
    // Create an ID using current timestamp
    var id = $.now();
    // Form html for a new option element to be added to the answers div - include ID
    var html = '<div class="col-md-3"></div>' +
      '<div class="form-group col-md-9 question-text">' +
      '<label class="option-input">Option: </label>' +
      '<input type="hidden" name="answers[' + id + '][question_id]" value="' + question_id + '">' +
      '<input type="text" class="form-control col-md-12 option-input" name="answers[' + id + '][answer]">' +
      '</div>';
    // Append the formed html to the answers div
    answers_div.append(html);
  });

  // Edit Form - Add question
  // If the add question button is clicked
  $('button.add-question').click(function (event) {
    // Prevent default behaviour of button (redirected user)
    event.preventDefault();

    category_id = $(this).attr("id");
    
    // Create an ID using current timestamp
    var id = $.now();
    // Form html for a new question element's input type to be added to the question div - include ID
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
    // Form html for a new question element's input to be added to the question div - include ID
    var question_html = '<div class="form-group col-md-9 question-text">' +
      '<label>Question Title: </label>' +
      '<input type="hidden" name="questions[' + id + '][category_id]" value="' + category_id + '">' +
      '<input type="text" class="form-control" name="questions[' + id + '][text]" value="">' +
      '</div>' +
      '</div>';

    // Append the formed html after the previous question div
    $(this).parent().before(input_type_html + question_html);
  });


  // Edit Form - Delete question
  // If the delete question button is clicked
  $('button.delete-question').click(function (event) {
    // Prevent default behaviour of button (redirected user)
    event.preventDefault();
    // Store a reference to the form this button is related to by taking it's 2nd parent element
    var form = $(this).parents()[2];
    // Store a reference to the question div this button is related to by taking it's 1st parent element
    var question_div = $(this).parents()[1];
    // Store the id attribute of the question div
    var question_id = question_div.id;
    // Redirect user to a page that deletes a question
    window.location.href = 'admin_area.php?view=editForm&id=' + question_id;
  });

  // Bind '.input-type select' change event to body so that dynamic element events also trigger
  $("body").on("change", ".input-type select", function (event) {
    // Submit the form
    $('form').submit();
  });

});