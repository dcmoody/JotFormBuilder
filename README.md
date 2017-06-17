# JotFormBuilder
Uses the JotForm API to pull JSON data and build forms on-the-fly. It uses an AJAX request to submit form IDs to a PHP script to pull JSON data, builds the forms using the returned JSON using JavaScript, and then submits them using another AJAX request. It also includes JavaScript functions to allow the user to display or hide the body of the forms.
jotforms.php builds the forms. It submits the IDs to jotformbuild.php, which submits JSON back. jotformsubmit takes the form submissions and submits them through the API.
