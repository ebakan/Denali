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
        var steps = $(element).find("fieldset");
        var count = steps.size();
        var submmitButtonName = "#" + options.submitButton;
        $(submmitButtonName).hide();

        $(element).before("<ul id='steps'></ul>");

        /**
         * Inserts buttons depending on beginning/end/middle frame   
         */
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

        checkDups(i + 1);
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
                    var id = $("#title" + 1).text();
                    if(id == i)
                        return true;
                    break;
                case 3:
                    var id = $("#title" + 1).text();
                    if(id == i)
                        return true;
                    break;
                case 4:
                    var id = $("#title" + 1).text();
                    if(id == i)
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
            if($("#length1").html() == 100 && $("#stepDesc0").hasClass('current'))
                islongnext = true;
            else if ($("#length2").html() == 100 && $("#stepDesc1").hasClass('current'))
                islongnext = true;
            else if ($("#length3").html() == 100 && $("#stepDesc2").hasClass('current'))
                islongnext = true;
            else
                islongnext = false;

        }).trigger('change');

        /**
         * Shows the next button when event is clicked for the first event.
         * Shows submit button when event is clicked and is on last session.
         */
        $("#mySelect").click(function() {
            $(".next").show();

            if($('#stepDesc3').hasClass('current')){
                $(submmitButtonName).show();
            }
        });

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

    
            if($("#title4").text().length <= 0)
                $(submmitButtonName).hide();
            else $(submmitButtonName).show();

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
                   if(!checkDups(3, text)) { 
                        $('#mySelect').append(
                            $('<option class="click3"></option>').attr("id", val).text(text)
                        );
                    }
                    
                });
            } else if($('#stepDesc3').hasClass('current')){
                $.each(fourthTimeSlotTitles, function(val, text) {
                       if(!checkDups(4, text)) { 
                            $('#mySelect').append(
                                $('<option class="click4"></option>').attr("id", val).text(text)
                            );
                        }
                    
                });
            }
        }

        /**
         * Deletes all options from select.
         */
        function delEvents()
        {
            $('#mySelect').find('option').remove();
        }
    


    }
})(jQuery); 


