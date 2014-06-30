// Content Form
$(function () {
    $('.error').hide();
    $('.text-input').css({backgroundColor:"#FFFFFF"});
    $('.text-input').focus(function () {
        $(this).css({backgroundColor:"#FCFCFC"});
    });
    $('.text-input').blur(function () {
        $(this).css({backgroundColor:"#FFFFFF"});
    });

    $(".cform-button").click(function () {
        // validate and process form
        // first hide any error messages
        $('.error').hide();

        var name = $("input#cname").val();
        if (name == "") {
            $("label#cname_error").show();
            $("input#cname").focus();
            return false;
        }
        var email = $("input#cemail").val();
        var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+.[a-z]{2,4}$/;
        console.log(filter.test(email));
        if (!filter.test(email)) {
            $("label#cemail_error").show();
            $("input#cemail").focus();
            return false;
        }
        var message = $("#input-cmessage").val();
		var vdcode = $("input#cvdcode").val();

        var dataString = 'name=' + name + '&email=' + email + '&message=' + message + '&vdcode=' + vdcode;
        //alert (dataString);return false;

        $.ajax({
            type:"POST",
            url:"/plus/information.php",
            data:dataString,
            success:function (data) {
				if(data == "vdcode"){
					$("label#cvdcode_error").show();
					$("input#cvdcode").focus();
            		return false;
				}
				//alert(data);
                $('#af-form').prepend("<div class=\"alert alert-success fade in\"><button class=\"close\" data-dismiss=\"alert\" type=\"button\">&times;</button><strong>感谢您对鹏魔工作室的关注！</strong> 我们将定期为您发送我们的最新动态消息。</div>");
                $('#af-form')[0].reset();
				changeAuthCode();
            }
        });
        return false;
    });
});

// Order Form
$(function () {
    $('.error').hide();
    $('.text-input').css({backgroundColor:"#FFFFFF"});
    $('.text-input').focus(function () {
        $(this).css({backgroundColor:"#FCFCFC"});
    });
    $('.text-input').blur(function () {
        $(this).css({backgroundColor:"#FFFFFF"});
    });

    $(".order-button").click(function () {
        // validate and process form
        // first hide any error messages
        $('.error').hide();

        var name = $("input#name").val();
        if (name == "") {
            $("label#name_error").show();
            $("input#name").focus();
            return false;
        }
        
		var phone = $("input#phone").val();
        var phone_filter = /^[1][0-9]{10}$/;
        console.log(phone_filter.test(phone));
        if (!phone_filter.test(phone)) {
            $("label#phone_error").show();
            $("input#phone").focus();
            return false;
        }
		
		var template = $("input#template").val();
		var domain = $("input#domain").val();
        var domain_filter = /^[a-zA-Z0-9]+.[a-z]{2,4}$/;
        console.log(domain_filter.test(domain));
        if (!domain_filter.test(domain)) {
            $("label#domain_error").show();
            $("input#domain").focus();
            return false;
        }
		
		var price = $("select#price").val();
		if(price == 0){
			$("label#price_error").show();
			$("select#price").focus();
            return false;
		}
		
		var vdcode = $("input#vdcode").val();
		if(price == 0){
			$("label#vdcode_error").show();
			$("input#vdcode").focus();
            return false;
		}
        var message = $("#input-message").val();

        var dataString = 'name=' + name + '&phone=' + phone + '&template=' + template + '&domain=' + domain + '&price=' + price + '&vdcode=' + vdcode + '&message=' + message;
        //alert (dataString);return false;

        $.ajax({
            type:"POST",
            url:"/plus/order.php",
            data:dataString,
            success:function (data) {
				if(data == "vdcode"){
					$("label#vdcode_error").show();
					$("input#vdcode").focus();
            		return false;
				}
                $('#af-form').prepend("<div class=\"alert alert-success fade in\"><button class=\"close\" data-dismiss=\"alert\" type=\"button\">&times;</button><strong>感谢您的关顾！</strong> 我们将尽快与您取得联系。</div>");
                $('#af-form')[0].reset();
				changeAuthCode();
            }
        });
        return false;
    });
});

// Footer Contact Form
$(function () {

    $('.ferror').hide();

    $(".footer-button").click(function () {
        // validate and process form
        // first hide any error messages
        $('.ferror').hide();

        var name = $("#inputName").val();
        if (name == "") {
            $("label#fname_error").show();
            $("#inputName").focus();
            return false;
        }
        var email = $("#inputEmail").val();
        var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
        console.log(filter.test(email));
        if (!filter.test(email)) {
            $("label#femail_error").show();
            $("#inputEmail").focus();
            return false;
        }
        var message = $("#inputMessage").val();
        if (message == "") {
            $("label#fmessage_error").show();
            $("#inputMessage").focus();
            return false;
        }

        var dataString = 'name=' + name + '&email=' + email + '&message=' + message;
        //alert (dataString);return false;

        $.ajax({
            type:"POST",
            url:"process.php",
            data:dataString,
            success:function () {
                $('#contact').append('<div class="modal hide" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-body"><p><strong class="color2">Your message was sent!</strong> - We will get back at You soon!</p></div><div class="modal-footer"><a href="#" class="btn" data-dismiss="modal">Close</a></div></div>');
                $('#contactModal').modal();
                $('#contact')[0].reset();
            }
        });
        return false;
    });
});