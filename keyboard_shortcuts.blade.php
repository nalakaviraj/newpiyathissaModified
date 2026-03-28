<script type="text/javascript">
	$(document).ready( function() {
		$('<style type="text/css">\
#product_list_body .product_box.pos-suggestion-active{outline:5px solid #f97316 !important;outline-offset:3px !important;box-shadow:0 20px 34px rgba(249,115,22,0.35) !important;transform:scale(1.06);z-index:3;}\
body.pos-cart-nav-active #pos_table tbody tr.pos-active-row td{background-color:rgba(249,115,22,0.18) !important;border-top:3px solid #f97316 !important;border-bottom:3px solid #f97316 !important;}\
body.pos-cart-nav-active #pos_table tbody tr.pos-active-row td:first-child{border-left:3px solid #f97316 !important;}\
body.pos-cart-nav-active #pos_table tbody tr.pos-active-row td:last-child{border-right:3px solid #f97316 !important;}\
</style>').appendTo('head');
		if (typeof Mousetrap !== 'undefined') {
			Mousetrap.stopCallback = function() {
				return false;
			};
			if (Mousetrap.prototype) {
				Mousetrap.prototype.stopCallback = function() {
					return false;
				};
			}
		}
		//shortcut for express checkout
		@if(!empty($shortcuts["pos"]["express_checkout"]) && ($pos_settings['disable_express_checkout'] == 0))
			Mousetrap.bind('{{$shortcuts["pos"]["express_checkout"]}}', function(e) {
				e.preventDefault();
				$('button.pos-express-finalize[data-pay_method="cash"]').trigger('click');
			});
		@endif

		//shortcut for cancel checkout
		@if(!empty($shortcuts["pos"]["cancel"]))
			Mousetrap.bind('{{$shortcuts["pos"]["cancel"]}}', function(e) {
				e.preventDefault();
				$('#pos-cancel').trigger('click');
			});
		@endif

		//shortcut for draft checkout
		@if(!empty($shortcuts["pos"]["draft"]) && ($pos_settings['disable_draft'] == 0))
			Mousetrap.bind('{{$shortcuts["pos"]["draft"]}}', function(e) {
				e.preventDefault();
				$('#pos-draft').trigger('click');
			});
		@endif

		//shortcut for draft pay & checkout
		@if(!empty($shortcuts["pos"]["pay_n_ckeckout"]) && ($pos_settings['disable_pay_checkout'] == 0))
			Mousetrap.bind('{{$shortcuts["pos"]["pay_n_ckeckout"]}}', function(e) {
				e.preventDefault();
				$('#pos-finalize').trigger('click');
			});
		@endif

		//shortcut for edit discount
		@if(!empty($shortcuts["pos"]["edit_discount"]) && ($pos_settings['disable_discount'] == 0))
			Mousetrap.bind('{{$shortcuts["pos"]["edit_discount"]}}', function(e) {
				e.preventDefault();
				$('#pos-edit-discount').trigger('click');
			});
		@endif

		//shortcut for edit tax
		@if(!empty($shortcuts["pos"]["edit_order_tax"]) && ($pos_settings['disable_order_tax'] == 0))
			Mousetrap.bind('{{$shortcuts["pos"]["edit_order_tax"]}}', function(e) {
				e.preventDefault();
				$('#pos-edit-tax').trigger('click');
			});
		@endif

		//shortcut for add payment row
		@if(!empty($shortcuts["pos"]["add_payment_row"]) && ($pos_settings['disable_pay_checkout'] == 0))
			var payment_modal = document.querySelector('#modal_payment');
			Mousetrap.bind('{{$shortcuts["pos"]["add_payment_row"]}}', function(e, combo) {
				if($('#modal_payment').is(':visible')){
					e.preventDefault();
					$('#add-payment-row').trigger('click');
				}
			});
		@endif

		//shortcut for add finalize payment
		@if(!empty($shortcuts["pos"]["finalize_payment"]) && ($pos_settings['disable_pay_checkout'] == 0))
			var payment_modal = document.querySelector('#modal_payment');
			Mousetrap(payment_modal).bind('{{$shortcuts["pos"]["finalize_payment"]}}', function(e, combo) {
				if($('#modal_payment').is(':visible')){
					e.preventDefault();
					$('#pos-save').trigger('click');
				}
			});
		@endif

		//Shortcuts to go recent product quantity
		@if(!empty($shortcuts["pos"]["recent_product_quantity"]))
			shortcut_length_prev = 0;
			shortcut_position_now = null;

			Mousetrap.bind('{{$shortcuts["pos"]["recent_product_quantity"]}}', function(e, combo) {
				var length_now = $('table#pos_table tr').length;

				if(length_now != shortcut_length_prev){
					shortcut_length_prev = length_now;
					shortcut_position_now = length_now;
				} else {
					shortcut_position_now = shortcut_position_now - 1;
				}

				var last_qty_field = $('table#pos_table tr').eq(shortcut_position_now - 1).contents().find('input.pos_quantity');
				if(last_qty_field.length >=1){
					last_qty_field.focus().select();
				} else {
					shortcut_position_now = length_now + 1;
					Mousetrap.trigger('{{$shortcuts["pos"]["recent_product_quantity"]}}');
				}
			});

			//On focus of quantity field go back to search when stop typing
			var timeout = null;
			$('table#pos_table').on('focus', 'input.pos_quantity', function () {
			    var that = this;

			    $(this).on('keyup', function(e){

			    	if (timeout !== null) {
			        	clearTimeout(timeout);
			    	}

			    	var code = e.keyCode || e.which;
			    	if (code != '9') {
    					timeout = setTimeout(function () {
			        		$('input#search_product').focus().select();
			    		}, 5000);
    				}
			    });
			});
		@endif

		//shortcut to go to add new products
		@if(!empty($shortcuts["pos"]["add_new_product"]))
			Mousetrap.bind('{{$shortcuts["pos"]["add_new_product"]}}', function(e) {
				$('input#search_product').focus().select();
			});
		@endif

		//shortcut for weighing scale
		@if(!empty($shortcuts["pos"]["weighing_scale"]))
			Mousetrap.bind('{{$shortcuts["pos"]["weighing_scale"]}}', function(e) {
				e.preventDefault();
				$('button#weighing_scale_btn').trigger('click');
			});
		@endif

		//POS keyboard navigation (suggestions + cart)
		var posKbSuggestionIndex = -1;
		var posKbCartNavActive = false;

		function posKbItems() {
			return $('#product_list_body .product_box');
		}
		function posKbClearSuggestion() {
			posKbSuggestionIndex = -1;
			posKbItems().removeClass('pos-suggestion-active pos-suggestion-zoom');
		}
		function posKbSuggestionCols($items) {
			if (!$items || !$items.length) {
				return 1;
			}
			var firstTop = $items.eq(0).offset().top;
			var cols = 0;
			$items.each(function() {
				var top = $(this).offset().top;
				if (Math.abs(top - firstTop) < 2) {
					cols++;
				} else {
					return false;
				}
			});
			return cols || 1;
		}
		function posKbSetSuggestion(index, animate) {
			var $items = posKbItems();
			if (!$items.length) {
				posKbSuggestionIndex = -1;
				return;
			}
			var maxIndex = $items.length - 1;
			var nextIndex = Math.max(0, Math.min(index, maxIndex));
			posKbSuggestionIndex = nextIndex;
			$items.removeClass('pos-suggestion-active');
			var $item = $items.eq(nextIndex);
			$item.addClass('pos-suggestion-active');
			$items.removeAttr('style');
			$item.css({
				outline: '5px solid #f97316',
				outlineOffset: '3px',
				boxShadow: '0 20px 34px rgba(249, 115, 22, 0.35)'
			});
			if (animate) {
				$item.addClass('pos-suggestion-zoom');
				setTimeout(function() {
					$item.removeClass('pos-suggestion-zoom');
				}, 220);
			}
			if ($item.length && $item[0] && typeof $item[0].scrollIntoView === 'function') {
				$item[0].scrollIntoView({ block: 'nearest', inline: 'nearest' });
			}
		}
		function posKbSelectSuggestion() {
			var $items = posKbItems();
			if (!$items.length) {
				return;
			}
			var idx = posKbSuggestionIndex;
			if (idx < 0) {
				idx = 0;
			}
			var $item = $items.eq(idx);
			if ($item.length) {
				$item.trigger('click');
			}
		}
		function posKbHandleSuggestion(combo) {
			var $items = posKbItems();
			if (!$items.length) {
				return false;
			}
			if (combo === 'enter') {
				if (posKbSuggestionIndex < 0) {
					posKbSetSuggestion(0, true);
				} else {
					posKbSelectSuggestion();
				}
				return true;
			}
			var cols = posKbSuggestionCols($items);
			var idx = posKbSuggestionIndex;
			if (idx < 0) {
				idx = 0;
			}
			var next = idx;
			if (combo === 'left') {
				next = idx - 1;
			} else if (combo === 'right') {
				next = idx + 1;
			} else if (combo === 'up') {
				next = idx - cols;
			} else if (combo === 'down') {
				next = idx + cols;
			}
			next = Math.max(0, Math.min(next, $items.length - 1));
			posKbSetSuggestion(next, false);
			return true;
		}

		function posKbCartRows() {
			return $('#pos_table tbody tr');
		}
		function posKbMarkCartRow($row) {
			if (!$row || !$row.length) {
				return;
			}
			$('#pos_table tbody tr').removeClass('pos-active-row');
			$('#pos_table tbody tr').removeAttr('style');
			$row.addClass('pos-active-row');
			$row.find('td').css({
				backgroundColor: 'rgba(249, 115, 22, 0.18)',
				borderTop: '3px solid #f97316',
				borderBottom: '3px solid #f97316'
			});
			$row.find('td:first-child').css('border-left', '3px solid #f97316');
			$row.find('td:last-child').css('border-right', '3px solid #f97316');
		}
		function posKbActiveCartRow() {
			var $active = $('#pos_table tbody tr.pos-active-row:last');
			if ($active.length) {
				return $active;
			}
			return $('#pos_table tbody tr:last');
		}
		function posKbSetCartNavActive(val) {
			if (typeof window.pos_cart_nav_active !== 'undefined') {
				window.pos_cart_nav_active = val;
			} else {
				posKbCartNavActive = val;
			}
		}
		function posKbIsCartNavActive() {
			if (typeof window.pos_cart_nav_active !== 'undefined') {
				return window.pos_cart_nav_active;
			}
			return posKbCartNavActive;
		}
		function posKbActivateCartNav() {
			if (typeof window.pos_cart_nav_activate === 'function') {
				window.pos_cart_nav_activate();
				return;
			}
			if (!posKbCartRows().length) {
				posKbSetCartNavActive(false);
				return;
			}
			posKbSetCartNavActive(true);
			$('body').addClass('pos-cart-nav-active');
			posKbClearSuggestion();
			var $active = posKbActiveCartRow();
			posKbMarkCartRow($active);
			$('input#search_product').blur();
		}
		function posKbDeactivateCartNav() {
			if (typeof window.pos_cart_nav_deactivate === 'function') {
				window.pos_cart_nav_deactivate();
				return;
			}
			posKbSetCartNavActive(false);
			$('body').removeClass('pos-cart-nav-active');
			$('input#search_product').focus().select();
		}
		function posKbMoveCart(delta) {
			if (typeof window.pos_cart_nav_move === 'function') {
				window.pos_cart_nav_move(delta);
				return;
			}
			var $rows = posKbCartRows();
			if (!$rows.length) {
				return;
			}
			var $active = posKbActiveCartRow();
			var idx = $rows.index($active);
			if (idx < 0) {
				idx = $rows.length - 1;
			}
			var next = Math.max(0, Math.min(idx + delta, $rows.length - 1));
			var $next = $rows.eq(next);
			posKbMarkCartRow($next);
			if ($next.length && $next[0] && typeof $next[0].scrollIntoView === 'function') {
				$next[0].scrollIntoView({ block: 'nearest', inline: 'nearest' });
			}
		}
		function posKbDeleteCartRow() {
			if (typeof window.pos_cart_nav_delete_active === 'function') {
				window.pos_cart_nav_delete_active();
				return;
			}
			var $rows = posKbCartRows();
			if (!$rows.length) {
				return;
			}
			var $active = posKbActiveCartRow();
			var $remove = $active.find('i.pos_remove_row').first();
			if ($remove.length) {
				$remove.trigger('click');
			} else {
				$active.remove();
				if (typeof window.pos_total_row === 'function') {
					window.pos_total_row();
				}
			}
		}

		$('#search_product').on('input', function() {
			posKbClearSuggestion();
		});

		Mousetrap.bind(['up', 'down', 'left', 'right', 'enter'], function(e, combo) {
			if (typeof window.pos_is_modal_open === 'function' && window.pos_is_modal_open()) {
				return;
			}
			if (!$('#search_product').is(':focus')) {
				return;
			}
			if (!posKbItems().length) {
				return;
			}
			e.preventDefault();
			posKbHandleSuggestion(combo);
		}, 'keydown');

		function posKbNormalizeKey(e) {
			if (!e) {
				return '';
			}
			if (e.key) {
				return e.key;
			}
			var code = e.which || e.keyCode;
			if (code === 13) return 'Enter';
			if (code === 27) return 'Escape';
			if (code === 37) return 'ArrowLeft';
			if (code === 38) return 'ArrowUp';
			if (code === 39) return 'ArrowRight';
			if (code === 40) return 'ArrowDown';
			if (code === 46) return 'Delete';
			if (code === 8) return 'Backspace';
			return '';
		}

		function posKbRawHandler(e) {
			if (typeof window.pos_is_modal_open === 'function' && window.pos_is_modal_open()) {
				return;
			}
			var key = posKbNormalizeKey(e);
			if (!key) {
				return;
			}
			var active = document.activeElement;
			var isSearch = active && active.id === 'search_product';
			if (isSearch && posKbItems().length) {
				if (key === 'ArrowUp' || key === 'ArrowDown' || key === 'ArrowLeft' || key === 'ArrowRight' || key === 'Enter') {
					e.preventDefault();
					if (key === 'Enter') {
						posKbHandleSuggestion('enter');
					} else if (key === 'ArrowUp') {
						posKbHandleSuggestion('up');
					} else if (key === 'ArrowDown') {
						posKbHandleSuggestion('down');
					} else if (key === 'ArrowLeft') {
						posKbHandleSuggestion('left');
					} else if (key === 'ArrowRight') {
						posKbHandleSuggestion('right');
					}
					return;
				}
			}

			if (key === 'Escape') {
				if (!posKbCartRows().length) {
					return;
				}
				e.preventDefault();
				if (posKbIsCartNavActive()) {
					posKbDeactivateCartNav();
				} else {
					posKbActivateCartNav();
				}
				return;
			}

			if (!posKbIsCartNavActive()) {
				return;
			}
			if (key === 'ArrowUp') {
				e.preventDefault();
				posKbMoveCart(-1);
			} else if (key === 'ArrowDown') {
				e.preventDefault();
				posKbMoveCart(1);
			} else if (key === 'Delete' || key === 'Backspace') {
				e.preventDefault();
				posKbDeleteCartRow();
			}
		}

		if (!window.__pos_kb_raw_bound) {
			window.addEventListener('keydown', posKbRawHandler, true);
			window.__pos_kb_raw_bound = true;
		}

		Mousetrap.bind('esc', function(e) {
			if (typeof window.pos_is_modal_open === 'function' && window.pos_is_modal_open()) {
				return;
			}
			if (!posKbCartRows().length) {
				return;
			}
			e.preventDefault();
			if (posKbIsCartNavActive()) {
				posKbDeactivateCartNav();
			} else {
				posKbActivateCartNav();
			}
		}, 'keydown');

		Mousetrap.bind(['up', 'down'], function(e, combo) {
			if (!posKbIsCartNavActive()) {
				return;
			}
			e.preventDefault();
			posKbMoveCart(combo === 'up' ? -1 : 1);
		}, 'keydown');

		Mousetrap.bind(['del', 'backspace'], function(e) {
			if (!posKbIsCartNavActive()) {
				return;
			}
			e.preventDefault();
			posKbDeleteCartRow();
		}, 'keydown');
	});
</script>
