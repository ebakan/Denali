/**
 * Main javascript for reg.php
 */
(function($) {
    $.fn.formToWizard = function(options) {
        options = $.extend({  
            submitButton: "" 
        }, options); 
        
        var element = this;

        var islongnext = new Boolean();
        var islongprev = new Boolean();
        var scottie1 = new Boolean();
        var scottie3 = new Boolean();
        var steps = $(element).find("fieldset");
        var count = steps.size();
        var submmitButtonName = "#" + options.submitButton;
        $(submmitButtonName).hide();
        $(".hidden").hide();
        hideSA();

        $(element).before("<ul id='steps'></ul>");

        /**
         * Inserts buttons depending on beginning/end/middle frame   
         */
        steps.each(function(i) {
            $(this).wrap("<div id='step" + i + "'></div>");
            $(this).append("<p id='step" + i + "commands'></p>");

            var name = $(this).find("legend").html();
            if(i==4)
                $("#steps").append("<li id='stepDesc" + i + "'>Confirmation </li>");
            else
                $("#steps").append("<li id='stepDesc" + i + "'>Session " + (i + 1) + "</li>");

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

        /**
         * Handles previous button and the click of it. Accounts for long/short events 
         */
        function createPrevButton(i) {
            var stepName = "step" + i;
            $("#" + stepName + "commands").append("<a href='#' id='" + stepName + "Prev' class='prev'>< Back</a>");

            $("#" + stepName + "Prev").bind("click", function(e) {
                delEvents();
                $("#" + stepName).hide();
                if(islongprev || ($("#id" + i).attr('class') == 'NULL')) {
                    $("#step" + (i - 1) + " fieldset div").removeAttr('class');
                    $("#step" + (i - 2)).show();
                    $(submmitButtonName).hide();
                    selectStep(i - 2);
                    islongnext = true;
                } else {
                    $("#step" + (i - 1)).show();
                    $(submmitButtonName).hide();
                    selectStep(i - 1);
                }
            });
        }

        /**
         * Handles next button and the click of it. Accounts for long/short events 
         */
        function createNextButton(i) {

            var stepName = "step" + i;
            $("#" + stepName + "commands").append("<a href='#' id='" + stepName + "Next' class='next'>Next ></a>");

            $("#" + stepName + "Next").bind("click", function(e) {
                e.preventDefault();
                delEvents();
                $("#message_display").html('');
                $("#" + stepName).hide();

                if(islongnext || ($("#id" + (i+1)).attr('class') == 'NULL'))
                {
                    $("#step" + (i + 2)).show();
                    $("#step" + (i + 1) + " fieldset div").attr('class', 'NULL');
                    $("#step" + (i + 1) + " fieldset div").html('');
                    selectStep(i + 2);
                    islongnext = false;
                    islongprev = true;
                } else {
                    $("#step" + (i + 1)).show();
                    //if (i + 2 == count)
                        //$(submmitButtonName).show();
                    selectStep(i + 1);
                    islongprev = false;
                }
            });
        }

        /**
        * Check for duplates so user cannot sign up for same event twice.
        */
        function checkDups(ts, i)
        {
            switch(ts){
                case 2:
                    var id = $("#title1").text();
                    if(id == i && id!='Planned Absence')
                        return true;
                    break;
                case 3:
                    var id = $("#title2").text();
                    if(id == i && id!='Planned Absence')
                        return true;
                    break;
                case 4:
                    var id = $("#title3").text();
                    if(id == i && id!='Planned Absence')
                        return true;
                    break;
                default: return false; break;
            }

        }


        /**
         * Adds/removes class current in order to style the Session indicators
         * Fills the valid events each time it is called
         */
        function selectStep(i) {
            $("#steps li").removeClass("current");
            $("#stepDesc" + i).addClass("current");
            fillevents();
        }

        
        /**
         * Every time an item is selected, it will set a boolean to see if it is long or short
         */
        $("#mySelect").change(function() {

             if($("#mySelect").val() != null)
                 $(".next").show();

            if($("#length1").html() == 100 && $("#stepDesc0").hasClass('current'))
                islongnext = true;
            else if ($("#length2").html() == 100 && $("#stepDesc1").hasClass('current'))
                islongnext = true;
            else if ($("#length3").html() == 100 && $("#stepDesc2").hasClass('current'))
                islongnext = true;
            else
                islongnext = false;


            switch($(".current").attr("id")) {
              case "stepDesc0":
                var count = $("#count1").text();
                var capacity = $("#capacity1").text();
                if(capacity=="Infinite") {
                    $("#spots1").text(capacity + " / " + capacity);
                } else {
                    $("#spots1").text((capacity-count) + " / " + capacity);
                }
             if($("#mySelect").val() != null)
                $("#spots1").show();
              break;
              case "stepDesc1":
                var count = $("#count2").text();
                var capacity = $("#capacity2").text();
                if(capacity=="Infinite") {
                    $("#spots2").text(capacity + " / " + capacity);
                } else {
                    $("#spots2").text((capacity-count) + " / " + capacity);
                }
             if($("#mySelect").val() != null)
                $("#spots2").show();
              break;
              case "stepDesc2":
                var count = $("#count3").text();
                var capacity = $("#capacity3").text();
                if(capacity=="Infinite") {
                    $("#spots3").text(capacity + " / " + capacity);
                } else {
                    $("#spots3").text((capacity-count) + " / " + capacity);
                }
             if($("#mySelect").val() != null)
                $("#spots3").show();
              break;
              case "stepDesc3":
                var count = $("#count4").text();
                var capacity = $("#capacity4").text();
                if(capacity=="Infinite") {
                    $("#spots4").text(capacity + " / " + capacity);
                } else {
                    $("#spots4").text((capacity-count) + " / " + capacity);
                }
             if($("#mySelect").val() != null)
                $("#spots4").show();
              break;
              default:
              break;
            }
        }).trigger('change');

        /**
         * Shows the next button when event is clicked for the first event.
         * Shows submit button when event is clicked and is on last session.
         */
             /*
        $("#mySelect").select(function() {
            alert("it worked");
            $(".next").show();

        });
        */

        /**
         * Handles showing/hiding next buttons when nothing is selected
         */
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

              break;
              default:
              break;
            }
        });

        /**
         * Fills the select pane with options of the session id
         */
        function fillevents()
        {
            $("#mySelect").show();

                //Fills mySelect with options
            if($('#stepDesc0').hasClass('current')){
                $.each(firstTimeSlotTitles, function(val, text) {
                
                    $('#mySelect').append(
                        $('<option class="click1"></option>').attr("id", val).text(text)
                    );
                    
                });
            } else if($('#stepDesc1').hasClass('current')){
                $.each(secondTimeSlotTitles, function(val, text) {
                   if(!checkDups(2, text)) { 
                        $('#mySelect').append(
                            $('<option class="click2"></option>').attr("id", val).text(text)
                        );
                   }
                    
                });
            } else if($('#stepDesc2').hasClass('current')){
                $.each(thirdTimeSlotTitles, function(val, text) {
                   if(!checkDups(3, text)) 
                        $('#mySelect').append(
                            $('<option class="click3"></option>').attr("id", val).text(text)
                        );
                    
                });
            } else if($('#stepDesc3').hasClass('current')){
                $.each(fourthTimeSlotTitles, function(val, text) {
                       if(!checkDups(4, text)) { 
                            $('#mySelect').append(
                                $('<option class="click4"></option>').attr("id", val).text(text)
                            );
                        }
                    
                });
            } else if($('#stepDesc4').hasClass('current')){
                $("#mySelect").hide();

                for(var x = 1; x <= 4; x++){
                   var longEv = $("#title" + x).attr('class');
                   var y = $("#title" + x).text();
                   if(longEv == "NULL")
                       $("#title5" + x).text("Long event placeholder."); 
                   else
                       $("#title5" + x).text(y); 
                }

                for(var x = 1; x <= 4; x++){
                   var longEv = $("#speaker" + x).attr('class');
                   var y = $("#speaker" + x).text();
                   if(longEv == "NULL")
                       $("#speaker5" + x).text("Long event placeholder."); 
                   else
                       $("#speaker5" + x).text(y); 
                }

                for(var x = 1; x <= 4; x++){
                   var longEv = $("#location" + x).attr('class');
                   var y = $("#location" + x).text();
                   if(longEv == "NULL")
                       $("#location5" + x).text("Long event placeholder."); 
                   else
                       $("#location5" + x).text(y); 
                }

                if(($("#id1").text() == 9 && $("#id3").text() != 10) || ($("#id3").text() == 10 && $("#id1").text() != 9)){
                    var done = false;
                    alert("You must choose Scottie Hill's presentation for both days if you would like to attend.");
                    $("#step0, #step1, #step2, #step3, #step4").hide();
                    if( $("#id1").text() == 9)
                    {
                        $("#step2").show();
                        selectStep(2);
                    }
                    else
                    {
                        $("#step0").show();
                        selectStep(0);
                    }
                } else
                    done = true;

                if(done)
                    $(submmitButtonName).show();

            }
        }


        /**
         * Deletes all options from select.
         */
        function delEvents()
        {
            $('#mySelect').find('option').remove();
        }

        /**
         * Hides all spotAvailable divs
         */
         function hideSA()
         {
             $("#spots1, #spots2, #spots3, #spots4").hide();
         }
    

        /**
         * Checks to see if event is filled up. Puts events in hidden inputs to be submitted.
         */
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
                   var errs = data.split(',');
                   realData = $.trim(data);
                   if(realData != "") {
                       var first = errs[0]-1;

                       alert("Sorry! One or more of the events ran out of space. Please choose another.");
                       for(var x = 0; x < errs.length; x ++)
                       {
                            $("#step" + (errs[x]-1) + " fieldset div").html('');
                       }

                        $(".next").hide();
                        $(submmitButtonName).hide();

                       $("#step0, #step1, #step2, #step3").hide();
                       $("#step" + first).show();
                       selectStep(first);
                   } else {
                       $.get(
                            "regstuds.php",
                          info + "&id=" + uid,
                            function(data) {
                               realData = $.trim(data);
                                if(realData == 1)
                                    window.location = "reviewEvents.php"
                                else
                                    window.location = "index.php"

                            },
                            "text");
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


