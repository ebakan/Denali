
(function($) {
    $.fn.formToWizard = function(options) {
        options = $.extend({  
            submitButton: "" 
        }, options); 
        
        var element = this;

        var steps = $(element).find("fieldset");
        var count = steps.size();
        var submmitButtonName = "#" + options.submitButton;
        $(submmitButtonName).hide();

        $(element).before("<ul id='steps'></ul>");

        steps.each(function(i) {
            $(this).wrap("<div id='step" + i + "'></div>");
            $(this).append("<p id='step" + i + "commands'></p>");

            var name = $(this).find("legend").html();
            $("#steps").append("<li id='stepDesc" + i + "'>Step " + (i + 1) + "<span>" + name + "</span></li>");

            if (i == 0) {
                createNextButton(i);
                selectStep(i);

                $(".next").hide();
            }
            else if (i == count - 1) {
                $("#step" + i).hide();
                createPrevButton(i);
            }
            else {
                $("#step" + i).hide();
                createPrevButton(i);
                createNextButton(i);
            }
        });

        function createPrevButton(i) {
            var stepName = "step" + i;
            $("#" + stepName + "commands").append("<a href='#' id='" + stepName + "Prev' class='prev'>< Back</a>");

            $("#" + stepName + "Prev").bind("click", function(e) {
                delEvents();
                $("#message_display").html('');
                $("#" + stepName).hide();
                $("#step" + (i - 1)).show();
                $(submmitButtonName).hide();
                selectStep(i - 1);
            });
        }

        function createNextButton(i) {

            var stepName = "step" + i;
            $("#" + stepName + "commands").append("<a href='#' id='" + stepName + "Next' class='next'>Next ></a>");

            $("#" + stepName + "Next").bind("click", function(e) {
                e.preventDefault();
                delEvents();
                $("#message_display").html('');
                $("#" + stepName).hide();
                $("#step" + (i + 1)).show();
                if (i + 2 == count)
                    $(submmitButtonName).show();
                selectStep(i + 1);
            });
        }

        function selectStep(i) {
            $("#steps li").removeClass("current");
            $("#stepDesc" + i).addClass("current");
            fillevents();
        }
        
        
        $("#mySelect").click(function() {
            $(".next").show();
            
            if($('#stepDesc3').hasClass('current')){
                $(submmitButtonName).show();
            }
        });

        $(".next, .prev").click(function() {
            switch($(".current").attr("id")) {
              case "stepDesc0":
                if($("#title1").text().length > 0)
                    $(".next").show();
                else
                    $(".next").hide();
              break;
              case "stepDesc1":
                if($("#title2").text().length > 0)
                    $(".next").show();
                else
                    $(".next").hide();
              break;
              case "stepDesc2":
                if($("#title3").text().length > 0)
                    $(".next").show();
                else
                    $(".next").hide();
              break;
              case "stepDesc3":
                if($("#title4").text().length > 0)
                    $(".next").show();
                else
                    $(".next").hide();

    
            if($("#title4").text().length <= 0)
                $(submmitButtonName).hide();

              break;
              default:
              break;
            }
        });

        function fillevents()
        {


                //Fills mySelect with options
            if($('#stepDesc0').hasClass('current')){
                $.each(firstTimeSlotTitles, function(val, text) {
                
                    $('#mySelect').append(
                        $('<option class="click1"></option>').attr("id", val).text(text)
                    );
                    
                });
            } else if($('#stepDesc1').hasClass('current')){
                $.each(secondTimeSlotTitles, function(val, text) {
                
                    $('#mySelect').append(
                        $('<option class="click2"></option>').attr("id", val).text(text)
                    );
                    
                });
            } else if($('#stepDesc2').hasClass('current')){
                $.each(thirdTimeSlotTitles, function(val, text) {
                
                    $('#mySelect').append(
                        $('<option class="click3"></option>').attr("id", val).text(text)
                    );
                    
                });
            } else if($('#stepDesc3').hasClass('current')){
                $.each(fourthTimeSlotTitles, function(val, text) {
                
                    $('#mySelect').append(
                        $('<option class="click4"></option>').attr("id", val).text(text)
                    );
                    
                });
            }
        }

        //delete events from previous session selection
        function delEvents()
        {
            $('#mySelect').find('option').remove();
        }
    
        $("#SaveAccount").click(function() {
            var evid1 = $('#id1').text();
            var evid2 = $('#id2').text();
            var evid3 = $('#id3').text();
            var evid4 = $('#id4').text();
            var info = "ev1=" + evid1 + "&ev2="+evid2+"&ev3="+evid3+"&ev4="+evid4;
           $.get(
               "ev.php",
               info,
               function(data) {
                   alert(data);
                   var errs = data.split(',');
                   if(data != "") {
                       var first = errs[0]-1;
                       alert(data);

                       for(var x=0; x< data.length; x++)
                       {
                            //put error message in top of page for each session      
                       }
                       $("#step0, #step1, #step2, #step3").hide();
                       $("#step" + first).show();
                       selectStep(first);
                   }

               },
               "text"
           );
           document.myform.eid1.value = evid1; 
           document.myform.eid2.value = evid2; 
           document.myform.eid3.value = evid3; 
           document.myform.eid4.value = evid4;; 
        
        });


    }
})(jQuery); 


