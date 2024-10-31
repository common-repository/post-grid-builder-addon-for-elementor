jQuery(document).ready(function($) {
  "use strict";
  $('.pgggo-container-ajax').on('click', '.page-numbers', function(event) {
    event.preventDefault();
    var pgggoThisElem = jQuery(this);
    var pgggoAjaxid =pgggoThisElem.parents('.pgggp-container-main').attr("data-pgggoeleid");
    var pgggoAjaxVar = 'pgggoAjax'+pgggoAjaxid;
    var pgggoAjax = window[pgggoAjaxVar];
    pgggoThisElem.parents('.elementor-widget-container').append('<div class="pgggo-dual-ring pgggo-container-loading"></div>');

    $.ajax({
      url: pgggoAjax.ajax_url,
      type: 'POST',
      context: this,
      data: {
        action: 'pgggo_ajax_pagination_loader',
        nonce: pgggoAjax.nonce,
        pgggosettings: pgggoAjax.pgggosettings,
        pgggopage: parseInt($(this).text()),
        pagesortorderaccent: '',
        pagesortorderdecnet: '',
      },
      success: function(response) {
        var pgggoPaginationElements = pgggoThisElem.parents('.pgggo-container').find('.pgggo-pagination span');
        var pgggoPaginationElementsA = pgggoThisElem.parents('.pgggo-container').find('.pgggo-pagination a');
        pgggoPaginationElements.removeClass('current');
        pgggoPaginationElementsA.removeClass('current');
        $(this).addClass('current');
        pgggoThisElem.parents('.pgggo-container').find('.pgggo-row').empty();
        pgggoThisElem.parents('.pgggo-container').find('.pgggo-row').append(response);
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-dual-ring').remove();
      },
    });
  });

  $('.pgggo-container-ajax-loadmore').on('click', 'div.pgggo-loadmore-button-ajax', function(event) {
    event.preventDefault();
    var pgggoThisElem = jQuery(this);
    var pgggoItemNum = pgggoThisElem.parents('.pgggo-container').find('.pgggo-container-ajaxon').attr('data-pgggopagenum');
    if (typeof pgggoItemNum === 'undefined') {
      var pgggoItemNum = pgggoThisElem.parents('.pgggo-container').attr('data-pgggopagenum');
    }
    var pgggoArgs = pgggoThisElem.parents('.pgggo-container').find('.pgggo-container-ajaxon').attr('data-pgggoajaxsorting');
    if (typeof pgggoArgs !== 'undefined') {
      var pgggoArgs = JSON.parse(pgggoArgs);
    } else {
      var pgggoArgs = {};
    }
    var pgggoItemNumInt = parseInt(pgggoItemNum) + 1;
    var pgggoAjaxid =pgggoThisElem.parents('.pgggp-container-main').attr("data-pgggoeleid");
    var pgggoAjaxVar = 'pgggoAjax'+pgggoAjaxid;
    var pgggoAjax = window[pgggoAjaxVar];

    pgggoThisElem.parents('.pgggo-container').append('<div class="pgggo-dual-ring pgggo-container-loading"></div>');
    $.ajax({
      url: pgggoAjax.ajax_url,
      type: 'POST',
      context: this,
      data: {
        action: 'pgggo_ajax_pagination_loader',
        nonce: pgggoAjax.nonce,
        pgggosettings: pgggoAjax.pgggosettings,
        pgggopage: pgggoItemNumInt,
        pagesortorderaccent: '',
        pagesortorderdecnet: '',
        pgggoargspass: pgggoArgs,
      },
      success: function(response) {
        pgggoThisElem.parents('.pgggo-container').attr('data-pgggopagenum', pgggoItemNumInt);
        pgggoThisElem.parents('.pgggo-container').find('.pgggo-row').append(response);
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-dual-ring').remove();
      },
    });
  });

  $('.pgggo-container-ajax-sorting').on('click', 'input.pgggocheckboxaccendinp', function() {
    var pgggoThisElem = jQuery(this);
    pgggoThisElem.parents('.pgggo-container').attr('data-pgggopagenum', "1");
    pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').attr('data-pgggopagenum', 1);
    pgggoThisElem.parents('.elementor-widget-container').append('<div class="pgggo-dual-ring pgggo-container-loading"></div>');
    var pgggoAjaxid =pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').attr("data-pgggoeleid");
    var pgggoAjaxVar = 'pgggoAjax'+pgggoAjaxid;
    var pgggoAjax = window[pgggoAjaxVar];
    $.ajax({
      url: pgggoAjax.ajax_url,
      type: 'POST',
      context: this,
      data: {
        action: 'pgggo_ajax_sorting_loader',
        nonce: pgggoAjax.nonce,
        pgggopage: "",
        pgggosettings: pgggoAjax.pgggosettings,
        pagesortorderaccent: 'on',
        pagesortorderdecnet: '',
      },
      success: function(response) {
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').empty();
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').append(response);
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-dual-ring').remove();
      },
    });
  });

  $('.pgggo-container-ajax-sorting').on('click', 'input.pgggocheckboxdecendinp', function() {
    var pgggoThisElem = jQuery(this);
    pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').attr('data-pgggopagenum', 1);
    pgggoThisElem.parents('.elementor-widget-container').append('<div class="pgggo-dual-ring pgggo-container-loading"></div>');
    var pgggoAjaxid =pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').attr("data-pgggoeleid");
    var pgggoAjaxVar = 'pgggoAjax'+pgggoAjaxid;
    var pgggoAjax = window[pgggoAjaxVar];
    $.ajax({
      url: pgggoAjax.ajax_url,
      type: 'POST',
      context: this,
      data: {
        action: 'pgggo_ajax_sorting_loader',
        nonce: pgggoAjax.nonce,
        pgggopage: "",
        pgggosettings: pgggoAjax.pgggosettings,
        pagesortorderdecnet: 'on',
        pagesortorderaccent: '',
      },
      success: function(response) {
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').empty();
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').append(response).show('slow');
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-dual-ring').remove();
      },
    });
  });

  $('.pgggo-container-ajax-sorting').on('click', '.pgggo-list-taxon input', function() {
    var pgggoThisElem = jQuery(this);
    pgggoThisElem.parents('.elementor-widget-container').append('<div class="pgggo-dual-ring pgggo-container-loading"></div>');
    var arrayPgggoData = pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-sort-container').attr('data-ajax-container');
    var arrayPgggoData = JSON.parse(arrayPgggoData);
    if ($(this).is(':checked')) {
      const [_, taxonomy, __, attr] = $(this).attr('name').split('-');
      arrayPgggoData[taxonomy] = arrayPgggoData[taxonomy] || [];
      arrayPgggoData[taxonomy].push(attr.split('[')[0]);
      pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-sort-container').attr('data-ajax-container', JSON.stringify(arrayPgggoData));
    } else {
      const [_, taxonomy, __, attr] = $(this).attr('name').split('-');
      arrayPgggoData[taxonomy] = arrayPgggoData[taxonomy] || [];
      Array.prototype.remove = function(el) {
        return this.splice(this.indexOf(el), 1);
      }
      arrayPgggoData[taxonomy].remove(attr.split('[')[0]);
      pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-sort-container').attr('data-ajax-container', JSON.stringify(arrayPgggoData));
    }
    var pgggoAjaxid =pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').attr("data-pgggoeleid");
    var pgggoAjaxVar = 'pgggoAjax'+pgggoAjaxid;
    var pgggoAjax = window[pgggoAjaxVar];
    $.ajax({
      url: pgggoAjax.ajax_url,
      type: 'POST',
      context: this,
      data: {
        action: 'pgggo_ajax_sorting_loader',
        nonce: pgggoAjax.nonce,
        pgggopage: "",
        pgggosettings: pgggoAjax.pgggosettings,
        pagesortorderdecnet: '',
        pagesortorderaccent: '',
        pagetaxondata: arrayPgggoData,
      },
      success: function(response) {
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').empty();
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').append(response).show('slow');
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-dual-ring').remove();
      },
    });
  });

  $('.pgggo-container-ajax-sorting').on('change', '.pgggo-select-taxon select', function() {
    var pgggoThisElem = jQuery(this);
    pgggoThisElem.parents('.elementor-widget-container').append('<div class="pgggo-dual-ring pgggo-container-loading"></div>');
    var arrayPgggoData = pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-sort-container').attr('data-ajax-container-select');
    var arrayPgggoData = JSON.parse(arrayPgggoData);
    var arraySelections = $(this).pgggodropdown('get value');
    const [_, __, ___, taxonomy, ____] = $(this).attr('name').split('-');
    arrayPgggoData[taxonomy] = arrayPgggoData[taxonomy] || [];
    arrayPgggoData[taxonomy].length = 0;
    if (Array.isArray(arraySelections)) {
      $(arraySelections).each(function(index, el) {
        arrayPgggoData[taxonomy].push(el);
      });
    } else {
      arrayPgggoData[taxonomy].push(arraySelections);
    }
    pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-sort-container').attr('data-ajax-container-select', JSON.stringify(arrayPgggoData));
    var pgggoAjaxid =pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').attr("data-pgggoeleid");
    var pgggoAjaxVar = 'pgggoAjax'+pgggoAjaxid;
    var pgggoAjax = window[pgggoAjaxVar];
    $.ajax({
      url: pgggoAjax.ajax_url,
      type: 'POST',
      context: this,
      data: {
        action: 'pgggo_ajax_sorting_loader',
        nonce: pgggoAjax.nonce,
        pgggopage: "",
        pgggosettings: pgggoAjax.pgggosettings,
        pagesortorderdecnet: '',
        pagesortorderaccent: '',
        pagetaxondataselect: arrayPgggoData,

      },
      success: function(response) {
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').empty();
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-container').append(response).show('slow');
        pgggoThisElem.parents('.elementor-widget-container').find('.pgggo-dual-ring').remove();
      },
    });
  });
});
