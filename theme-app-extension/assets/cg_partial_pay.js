jQuery(document).ready(function () {  // ERROR!  jQuery library hasn't been run yet, so we can't use jQuery to select the document!



  function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
  function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  function checkCookie(cname, cvalue) {
    var getcg_cookie = getCookie(cname);
    if (getcg_cookie != "") {
    } else {
      setCookie(cname, cvalue, 365);
    }
  }

  jQuery(".form button[type=submit]").addClass('cg_pre_pay');
  jQuery(".shopify-payment-button").css('display', 'none');

  $('.form').append('<button name="add" id="add_part_btn" type="button" style="background:#000;color:#fff;font-size:15px;font-weight:normal; width: 100%; margin-top: 5px;border-radius:0px;" data-sdtooltip="Available soon" class="btn  hvr-no sd_preorder top" value="Pay Full">Pay Full </button><div class="price_section"></div>');
  jQuery(".cg_pre_pay").css('display', 'none');

  console.log(document.cookie);
  var varidid = window.ShopifyAnalytics.meta.product.variants[0].id;
  var shop_name = document.location.origin;
  $.ajax({
    type: "POST",
    url: 'https://phpstack-877186-3039039.cloudwaysapps.com/public/index.php/frontend-handler',
    data: 'shopname=' + shop_name + '&vid=' + varidid + '&pid=' + window.ShopifyAnalytics.meta.page.resourceId,
    success: function (response) {
      if (response == 'not_found') {
        jQuery("#cg_pay_type").hide();
        jQuery(".shopify-payment-button").show();
        jQuery(".cg_pre_pay").show();
      } else {
        jQuery("#cg_pay_type").show();
        var result_array = JSON.parse(response);
        // console.log(result_array);
        // console.log(result_array['full_price']);

        setCookie(varidid, result_array['pro_pack'], 365);
        // checkCookie(varidid,result_array['pro_pack'],"");


        jQuery(".price_section").html('<span id="full_pay" class="sd_partial_msg"><b class="sd-full-money">Pay full payment -  <span class="sd-money">$' + result_array['full_price'] + '</span> </b></span><span style="display:none;" id="partial_pay" class="sd_partial_msg"><b class="sd-full-money">Pay initial payment -  <span class="sd-money">$' + result_array['partial_price'] + '</span> </b></span>');
      }

    }
  });
  var unique_uid = Date.now().toString(36) + Math.random().toString(36).substring(2);


  jQuery('#add_part_btn').on('click', function (event) {
    
    //console.log($('.form').serialize());
    var pqty = jQuery("input[name=quantity]").val();
    var pay_type = jQuery("input[name='sd_part_full']:checked").val();
    // setCookie(varidid)



    let formData = {
      'items': [{
        'id': varidid,
        'quantity': pqty,
        properties: {
          'PAYMENT_TYPE': pay_type
        }
      }]
    };
    fetch(window.Shopify.routes.root + 'cart/add.js', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    })
      .then(response => {
        window.location.href = document.location.origin + '/cart';
        //console.log(response.json());
      })
      .catch((error) => {
        console.error('Error:', error);
      });

  });
  jQuery('.sd_partial_fullpart').on('change', function (event) {
    //alert($(this).val());
    if ($(this).val() == 'sd_full') {
      jQuery("#full_pay").show();
      jQuery("#partial_pay").hide();
      jQuery("#add_part_btn").val('Pay Full');
      document.getElementById('add_part_btn').textContent = 'Pay Full';
    } else {
      jQuery("#full_pay").hide();
      jQuery("#partial_pay").show();
      jQuery("#add_part_btn").val('Pay Partial');
      document.getElementById('add_part_btn').textContent = 'Pay Partial';
    }
  });




  // var myVeryOwnProductId = '{{product.id}}';
  // console.log(myVeryOwnProductId);
  // return false;
  //  $.ajax({
  //     type: 'POST', 
  //     url: '/cart/add.js',
  //     dataType: 'json', 
  //     data: JSON.stringify(formData),
  //     success: function (response) {
  //       console.log(response)
  //     }
  //   });

  // jQuery.getJSON('/cart.js', function(cart) {
  //   for(var i=0; i<cart.items.length; i++){
  //     var item = cart.items[i];
  //     console.log(item.id);
  //     var price_element = $('#line-total-' + item.id);
  //     price_element.html(Shopify.formatMoney(item.line_price).replace('$','£'));
  //   };
  // });

  //var product_price2 = '{{ 2500 | money_without_trailing_zeros }}';



  // jQuery.getJSON('/cart.js', function(cart) {
  //   for(var i=0; i<cart.items.length; i++){
  //     var item = cart.items[i];
  //     var price_element = $('#line-total-' + item.id);
  //     console.log(item.line_price);
  //     price_element.html(Shopify.formatMoney(item.line_price).replace('$','£'))
  //   }
  // });

  // var myVeryId = '{{product.id}}';
  // var product_price = '{{product.price}}';
  // var full_price = '{{product.price | money}}';
  // var partial_pay = (20/100) * product_price;

  // console.log('prodyct info13');
  // console.log(product_price);
  // console.log(partial_pay);
  // console.log(full_price);

  // jQuery('.sd_partial_fullpart').on('change', function(event){
  //      if($(this).val() == 'sd_full'){

  //         jQuery(".sd-full-money").html('Pay full payment -  <span class="sd-money">'+full_price+'</span> </b>')

  //      }else {
  //         jQuery(".sd-full-money").html('Pay initial payment -  <span class="sd-money">'+partial_pay+'</span> </b>')
  //      }
  //   });

  // jQuery(".sd_partial_msg").html('Pay full payment -  <span class="sd-money">'+full_price+'</span> ');

  // jQuery.getJSON('/cart.js', function(cart) {
  //     for(var i=0; i<cart.items.length; i++){
  //       var item = cart.items[i];
  //       var price_element = $('#line-total-' + item.id);
  //       console.log(item.line_price);

  //     }
  //   });


  

});


