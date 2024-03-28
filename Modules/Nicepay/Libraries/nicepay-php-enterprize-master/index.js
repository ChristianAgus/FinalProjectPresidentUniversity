$("#va-btn").click(function() {
  $("#va-form").show();
  $("#cc-form").hide();
  $("#reckeyin-form").hide();
  $("#recthreeds-form").hide();
  $("#va-btn").addClass("active");
  $("#cc-btn").removeClass("active");
  $("#reckeyin-btn").removeClass("active");
  $("#recthreeds-btn").removeClass("active");
});

$("#cc-btn").click(function() {
  $("#va-form").hide();
  $("#cc-form").show();
  $("#reckeyin-form").hide();
  $("#recthreeds-form").hide();
  $("#va-btn").removeClass("active");
  $("#cc-btn").addClass("active");
  $("#reckeyin-btn").removeClass("active");
  $("#recthreeds-btn").removeClass("active");
});

$("#reckeyin-btn").click(function() {
  $("#va-form").hide();
  $("#cc-form").hide();
  $("#reckeyin-form").show();
  $("#recthreeds-form").hide();
  $("#va-btn").removeClass("active");
  $("#cc-btn").removeClass("active");
  $("#reckeyin-btn").addClass("active");
  $("#recthreeds-btn").removeClass("active");
});

$("#recthreeds-btn").click(function() {
  $("#va-form").hide();
  $("#cc-form").hide();
  $("#reckeyin-form").hide();
  $("#recthreeds-form").show();
  $("#va-btn").removeClass("active");
  $("#cc-btn").removeClass("active");
  $("#reckeyin-btn").removeClass("active");
  $("#recthreeds-btn").addClass("active");
});
