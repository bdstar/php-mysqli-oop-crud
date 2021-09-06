$(document).ready(function() {
    $('#word-list').DataTable();
} );


// Disable form submissions if there are invalid fields
(function() {
    'use strict';
    window.addEventListener('load', function() {
      // Get the forms we want to add validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            //console.log("submitted");
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    }, false);
  })();




// Add and Edit operation
$(function () {
  $('#save-tag').on('submit', function (e) {
    e.preventDefault();
    var type = $(this).data("type");
    var url = (type=="Update") ? 'database.php?page=tag&action=update' : 'database.php?page=tag&action=insert';
    $.ajax({
      type: 'post',
      dataType: "json",
      url: url,
      data: $('form').serialize(),
      success: function (data) {
        if(data.result=="failed"){
          $("#operation-result").css("display", "block");
          $("#operation-result").removeClass("alert-success");
          $("#operation-result").addClass("alert-danger");
          $("#operation-result").html("<strong>"+data.result+"!</strong> <i>"+data.message+"</i> because of <b>"+data.msg+"</b>");
          toastr.error('Tag not '+type+'!', 'Tag!')
        }else{
          $("#operation-result").css("display", "block");
          $("#operation-result").removeClass("alert-danger");
          $("#operation-result").addClass("alert-success");
          $("#operation-result").html("<strong>"+data.result+"!</strong> <i>"+data.message+"</i> because of <b>"+data.msg+"</b>");
          toastr.success('Tag successfully '+type+'!', 'Tag');
        }

        if(type=="Add"){
          $("#save-tag").removeClass("was-validated");
          $('#name').val("");
          $('#description').val("");
        }
      },
      fail: function(xhr, textStatus, errorThrown){
          toastr.error('Request Failed.', 'Failed!')
      }
    });
  });

});




 
//Delete Operation
function deleteData(id) {
  console.log("Delete id: ",id);
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
  })
  
  swalWithBootstrapButtons.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'No, cancel!',
    reverseButtons: true,
    preConfirm: (login) => {
      return fetch('database.php?page=tag&action=delete&id='+id)
        .then(response => {
          if (!response.ok) {
            throw new Error(response.statusText)
          }
          return response.json()
        })
        .catch(error => {
          Swal.showValidationMessage(
            `Request failed: ${error}`
          )
        })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    if (result.isConfirmed) {
      swalWithBootstrapButtons.fire('Deleted!', 'Your file has been deleted.','success')
    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
    swalWithBootstrapButtons.fire('Cancelled', 'Your data is safe :)', 'error')
    }
  })
}