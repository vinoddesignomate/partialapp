document.addEventListener('DOMContentLoaded', function() {

    // This event will be run after the deferred scripts above, so jQuery exists now
    $('#add-btn').on('click', function(event){
      alert("clicked")
    })
  });
