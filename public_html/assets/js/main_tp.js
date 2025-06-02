let baseDomain = window.location.origin;

function number_format(number, decimals) {
	var fixed = number.toFixed(decimals);
	return fixed.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function updatePosPrice() {
	var selectedOption = $('#posCurrencyId option:selected');
    var posType = $('#posType').val();
    var askPrice = selectedOption.data('ask');
    var bidPrice = selectedOption.data('bid');
	if (posType == "1") {
		$('#posPrice').val(askPrice);
	} else if (posType == "2") {
		$('#posPrice').val(bidPrice);
	}
}

function requiredMargin() {
	var selectedOption = $('#posCurrencyId option:selected');
	var currencyName   = selectedOption.data('symbol');
	var contractSize   = selectedOption.data('contract-size');
	var baseCurrency   = selectedOption.data('base');
	var percentage     = selectedOption.data('percentage');
	var posAmount      = $('#posAmount').val();
	var posPrice       = $('#posPrice').val();
	var leverage       = selectedOption.data('leverage');
	if($('#posCurrencyId').val() != 0){
		if (currencyName.startsWith("USD") || (!currencyName.includes("USD") && baseCurrency !== "USD")) {
			var reqMargin = ((posAmount * posPrice * contractSize) / leverage) * (1/posPrice);
		}else{
			var reqMargin = ((posAmount * posPrice * contractSize) / leverage) * (1/posPrice);
		}

		if (percentage == 1) {
			var reqMargin = (posAmount * posPrice * contractSize) / leverage;
		}
	
		console.log(posAmount, posPrice, leverage, contractSize, reqMargin);
		$('#reqMargin').val(parseFloat(reqMargin).toFixed(3));
	}

}
let eventSource;
let sseTimeout;

function startAjaxPnl() {
	if ($.active > 0) {
        return;
    }

    var selectedOption = $('#posCurrencyId option:selected');
    var assetId = selectedOption.val();

	$.ajax({
		url: '/client/mainTp/get_pnl/' + client_id + '/' + assetId,
		method: 'GET',
		dataType: 'json',
		success: function (data) {
			selectedOption.data('ask', data.ask || 0.00);
			selectedOption.data('bid', data.bid || 0.00);
			updatePosPrice();
			requiredMargin();

			data.orders.forEach(order => {
				$('.pnl[data-order-id="' + order.id + '"]').find('div').text(parseFloat(order.pnl).toFixed(2)).removeClass('text-danger text-success').addClass(parseFloat(order.pnl) < 0 ? 'text-danger' : 'text-success');
				$('.current_price[data-order-id="' + order.id + '"]').text(order.close_price);
				$('.amount[data-order-id="' + order.id + '"]').text(parseFloat(order.amount).toFixed(2));
				$('.margin[data-order-id="' + order.id + '"]').text(parseFloat(order.required_margin).toFixed(2));
				$('.open_price[data-order-id="' + order.id + '"]').text(order.open_price);
				$('.comment[data-order-id="' + order.id + '"]').text(order.comment);
				$('.type[data-order-id="' + order.id + '"]').text(order.type == 1 ? 'Buy' : 'Sell');
				const $el = $('.editOpenOrder[data-order-id="' + order.id + '"]');
				$el.data('trxdate', order.created_at);
				$el.attr('data-trxdate', order.created_at);	
				$el.data('comment', order.comment);
				$el.data('amount', order.amount);
				$el.data('price', order.open_price);
				$el.data('type', order.type);
			});

			$('.currentPL').text('$ ' + data.pnl)
				.removeClass('text-red text-green')
				.addClass(parseFloat(data.pnl) < 0 ? 'text-red' : 'text-green');

			$('.equity').text('$ ' + data.equity)
				.removeClass('text-red text-green')
				.addClass(parseFloat(data.equity) < 0 ? 'text-red' : 'text-green');

			$('.online').text(data.online_text)
				.closest('.d-flex')
				.removeClass('text-warning text-success')
				.addClass(data.online ? 'text-success' : 'text-warning');
				
			startAjaxPnl();
		},
		error: function () {
			console.error('Error fetching PnL data.');
			startAjaxPnl();
		}
	});

}
$(document).on('click', '.closePositionBtn', function() {
    $('#closePosition').attr('action', $(this).attr('formaction'));
    $('#closeAmount').val($(this).attr('data-amount'));
});

$(document).on('click', '.multiClosePositionBtn', function() {
    $('#multiClosePosition').attr('action', $(this).attr('formaction'));
});

$(document).on('change', '#posCurrencyId', function() {
    $('#assetName').val($(this).find('option:selected').text());
});

$(document).ready(function(){
	setInterval(startAjaxPnl, 100);

	$(document).on('click', '.editClosedOrder', function() {
        let trxOpenDate = $(this).data('trxopendate');
        let commission  = $(this).data('com');
        let formAction  = $(this).attr('formaction');
        let openPrice   = $(this).data('open-price');
        let refPrice    = $(this).data('refprice');
        let comment     = $(this).data('comment');
        let trxDate     = $(this).data('trxdate');
        let amount      = $(this).data('amount');
        let script      = $(this).data('script');
        let price       = $(this).data('price');
        let type        = $(this).data('type');

        $('#closeTrxOpenDate').val(trxOpenDate);
        $('#editCloseAmount').val(amount);
        $('#closeComment').val(comment);
        $('#closeTrxDate').val(trxDate);
        $('#closePrice').val(price);
        $('#editScript').val(script);
        $('#openPrice').val(openPrice);
		$('#closeType').val(type).trigger('change');
        $('#closeCom').val(commission);
        $('#refPrice').val(refPrice);

        $('#editClosePosition').attr('action', formAction);
    });

	$(document).on('click', '.reopenClosedOrder', function() {
        let formAction = $(this).attr('formaction');
        $('#reopenClosePosition').attr('action', formAction);
    });

	$(document).on('click', '.editOpenOrder', function() {
        let commission = $(this).data('com');
        let formAction = $(this).attr('formaction');
        let comment    = $(this).data('comment');
        let trxDate    = $(this).data('trxdate');
        let amount     = $(this).data('amount');
		let script     = $(this).data('script');
        let price      = $(this).data('price');
        let type       = $(this).data('type');


        $('#posOpenCommission').val(commission);
		$('#editPosScript').val(script);
        $('#editPosAmount').val(amount);
        $('#editPosPrice').val(price);
		$('#editPosType').val(type).trigger('change');
        $('#posComment').val(comment);
        $('#posDate').val(trxDate);

        $('#editOpenPosition').attr('action', formAction);
    });

	$('#editOpenPosition').on('submit', function(e) {
		e.preventDefault();
		let $form = $(this);
		$.ajax({
			url: $form.attr('action'),
			method: 'POST',
			data: $form.serialize(),
			success: function(response) {
				if (response.success && response.order) {
					let orderId = response.order.id;
					let newCreatedAt = response.order.created_at; // formatted as 'd/m/Y H:i'
					$(`button.editOpenOrder[data-order-id="${orderId}"]`)
						.closest('tr')
						.find('td:first .d-flex.align-items-center')
						.contents()
						.filter(function() {
							// Node type 3 is a text node, and it should contain the date
							return this.nodeType === 3 && $.trim(this.nodeValue) !== '';
						})
						.last()
						.replaceWith(' ' + newCreatedAt);
				}
				// Optionally close modal, show success, etc.
			}
		});
	});
	
	$(document).on('click', '.editRequest', function() {
        let paymentType = $(this).data('payment-type');
        let bankDetails = $(this).data('bank-details');
        let formAction  = $(this).attr('formaction');
        let comment     = $(this).data('comment');
        let type        = $(this).data('type');
        let usdt        = $(this).data('usdt');
        let amount      = $(this).data('amount');
        let bank        = $(this).data('bank');
        let date        = $(this).data('date');

		$('.bank_details').addClass('d-none');
		$('.deposit').addClass('d-none');
		$('.usdt').addClass('d-none');
		$('.bank_details').find('input').val('');
		$('.usdt').find('input').val('');
		$('#bankId').val('').trigger('change');
		if (type == 'deposit') {
			$('.deposit').removeClass('d-none');
		}
		if (paymentType == 'USDT') {
			$('.usdt').removeClass('d-none');
			$('#editRequestUsdt').val(usdt);
		}
		if (paymentType == 'bank') {
			$('.bank_details').removeClass('d-none');
			$('#editRequestIban').val(bankDetails.iban);
			$('#editRequestSwift').val(bankDetails.swift);
			$('#editRequestCurrency').val(bankDetails.currency);
			$('#editRequestBankName').val(bankDetails.bank_name);
			$('#editRequestBankCountry').val(bankDetails.bank_country);
			$('#editRequestBankAddress').val(bankDetails.bank_address);
			$('#editRequestBeneficiaryName').val(bankDetails.beneficiary_name);
			$('#editRequestAbaRoutingNumber').val(bankDetails.aba_routing_number);
			$('#editRequestBeneficiaryAddress').val(bankDetails.beneficiary_address);
			$('#editRequestBeneficiaryCountry').val(bankDetails.beneficiary_country);
		}

        $('#editRequestAmount').val(amount);
        $('#editRequestDate').val(date);
        $('#requestComment').val(comment);
		$('#bankId').val(bank);

        $('#EditRequest').attr('action', formAction);
    });

	$(document).on('click', '.edit_money_trx', function() {
        let formAction = $(this).attr('formaction');
        let trxdate    = $(this).data('trxdate');
        let comment    = $(this).data('comment');
        let amount     = $(this).data('amount');
        let type       = $(this).data('type');

		$('#editTrxComment').val(comment);
        $('#editTrxAmount').val(amount);
		$('#editTrxType').val(type).trigger('change');
		$('.editTrxType').val(type);
		$('#editTrxDate').val(trxdate);
		
        $('#editTrx').attr('action', formAction);
    });

	$(document).on('click', '.reqType', function() {
		$('.requestPaymentType').addClass('d-none');
		$('.requestDeposit').addClass('d-none');
		$('.requestUsdt').addClass('d-none');
		$('.request_bank_details').addClass('d-none');

		if ($(this).val() == 'withdraw') {
			$('.requestPaymentType').removeClass('d-none');
		}
		else{
			$('.requestDeposit').removeClass('d-none');
		}
	});

	$(document).on('click', '.reqPaymentType', function() {
		$('.requestUsdt').addClass('d-none');
		$('.request_bank_details').addClass('d-none');

		if ($(this).val() == 'usdt') {
			$('.requestUsdt').removeClass('d-none');
			$('.requestUsdt').find('input').attr('required', true);
		}
		else{
			$('.request_bank_details').removeClass('d-none');
			$('.request_bank_details').find('input');
		}
	});
	
	if ($('.trxDate').length > 0) {
		$('.trxDate').inputmask({
			mask: "9999-99-99 99:99:99.999",
			placeholder: "0000-00-00 00:00:00.000",
			clearIncomplete: false
		});
	}

	$('#open_order_section').on('change input', '#posAmount', function() {
        var posAmount = $(this).val().trim();

        if (posAmount === "" || parseFloat(posAmount) < 0.01) {
            $('.min-amount').removeClass('d-none');
            $('.openOrderBtn').attr('disabled', true);
        } else {
            $('.min-amount').addClass('d-none');
            $('.openOrderBtn').attr('disabled', false);
        }
    });
	
	$('#editPosAmount').on('change input', function() {
		var posAmount = $(this).val().trim();
		
		if (posAmount === "" || parseFloat(posAmount) < 0.01) {
			$('.min-amount-open').removeClass('d-none');
			$('.updateOrderBtn').attr('disabled', true);
		} else {
			$('.min-amount-open').addClass('d-none');
			$('.updateOrderBtn').attr('disabled', false);
		}
	});

	$('#editCloseAmount').on('change input', function() {
		var posAmount = $(this).val().trim();
		
		if (posAmount === "" || parseFloat(posAmount) < 0.01) {
			$('.min-amount-close').removeClass('d-none');
			$('.UpdateClosedOrder').attr('disabled', true);
		} else {
			$('.min-amount-close').addClass('d-none');
			$('.UpdateClosedOrder').attr('disabled', false);
		}
	});
});
