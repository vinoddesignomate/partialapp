//jQuery( document ).ready(function() {  // ERROR!  jQuery library hasn't been run yet, so we can't use jQuery to select the document!

   // $('#add-btn').on('click', function(event){
     // alert("clicked")
    //}) 
  //});

  var string = "shopify_vinod";
  var encodestfn = btoa(string);
  var shop_name = document.location.origin;

  $.ajax({
    type: "POST",
    url: 'https://bigthinxapp.herokuapp.com/public/index.php/check-size',
    data: 'shopname=' + shop_name + '&pid=' + window.ShopifyAnalytics.meta.page.resourceId + '&token=' + encodestfn,
    // dataType: "json",
    success: function (response) {
     // $("#dis_product_size").append(response);
    }
  });
