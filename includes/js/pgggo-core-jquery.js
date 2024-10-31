jQuery(document).ready(function(c) {
  "use strict";
  c(".pgggo-clear-active-filters").on("click", function(){
     window.location.href = window.location.href.split('?')[0];
  });
  c(".ui.pgggodropdown").pgggodropdown({
    clearable: !0,
    maxSelections: 3,
    transition: "slide right"
  }), c(".pgggo-sort-collapse-button").on("click", function() {
    c(".pgggo-sort-collapase-content").slideToggle("slow")
  }), c("li.pgggo-li-checkbox").each(function() {
    c(".pgggocheckboxaccendinp").on("click", function() {
      1 == c("input.pgggocheckboxaccendinp").is(":checked") ? c("input.pgggocheckboxdecendinp").prop("checked", !1) : c("input.pgggocheckboxdecendinp").prop("checked", !0)
    }), c(".pgggocheckboxdecendinp").on("click", function() {
      1 == c("input.pgggocheckboxdecendinp").is(":checked") ? c("input.pgggocheckboxaccendinp").prop("checked", !1) : c("input.pgggocheckboxaccendinp").prop("checked", !0)
    })
  })
});
