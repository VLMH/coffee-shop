<template>
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="form-horizontal" v-on:submit.prevent="onSubmit">

        <h3><u>Order</u></h3>

        <div class="form-group">
          <label for="name" class="col-sm-4 control-label">Name</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="name" name="name" placeholder="Customer Name" v-model="payment['customerName']" required>
          </div>
        </div>
        <div class="form-group">
          <label for="tel" class="col-sm-4 control-label">Tel</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="tel" name="tel" placeholder="Phone Number" v-model="payment['tel']" maxlength="8" required>
          </div>
        </div>
        <div class="form-group">
          <label for="currency" class="col-sm-4 control-label">Currency</label>
          <div class="col-sm-8">
            <select id="currency" class="form-control" name="currency" v-model="payment['currency']">
              <option v-for="c in currencyList" :value="c">{{ c.toUpperCase() }}</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="amount" class="col-sm-4 control-label">Amount</label>
          <div class="col-sm-8">
            <div class="input-group">
              <span class="input-group-addon">$</span>
              <input type="number" min="0" id="amount" class="form-control" name="amount" v-model="payment['amount']" required>
            </div>
          </div>
        </div>

        <h3><u>Payment</u></h3>

        <div class="form-group">
          <label for="card-holder-name" class="col-sm-4 control-label">Card holder name</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="card-holder-name" name="card-holder-name" placeholder="Card holder name" v-model="payment['cardHolderName']" required>
          </div>
        </div>
        <div class="form-group">
          <label for="card-number" class="col-sm-4 control-label">Card number</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="card-number" name="card-number" placeholder="4111 1111 1111 1111" v-model="payment['cardNumber']" maxlength="16" required>
          </div>
        </div>
        <div class="form-group">
          <label for="exp-date" class="col-sm-4 control-label">Expiration</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="exp-date" name="exp-date" placeholder="MM/YYYY" v-model="payment['expDate']" maxlength="7" required>
          </div>
        </div>
        <div class="form-group">
          <label for="ccv" class="col-sm-4 control-label">CCV</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="ccv" name="ccv" placeholder="CCV" v-model="payment['ccv']" maxlength="4" required>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-4 col-sm-8">
            <button type="submit" class="btn btn-primary" v-on:click="onSubmit">Submit</button>
          </div>
        </div>

      </form>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['braintree_auth'],
    data() {
      return {
        braintreeAuthorization: window.Laravel.braintreeAuth,
        currencyList: ['hkd','usd','aud','eur','jpy','cny'],
        payment: {
          customerName: '',
          tel: '',
          currency: 'hkd',
          amount: 10.00,
          cardHolderName: '',
          cardNumber: '',
          expDate: '',
          ccv: '',
        }
      }
    },
    methods: {
      onSubmit: function() {
        this._payByBraintree(this.payment);
      },
      _payByBraintree: function(paymentInfo) {
        braintree.client.create({
          authorization: this.braintreeAuthorization
        }, function (clientErr, clientInstance) {
          if (clientErr) {
            // Handle error in client creation
            console.error('client error', clientErr);
            return;
          }

          clientInstance.request({
            endpoint: 'payment_methods/credit_cards',
            method: 'post',
            data: {
              creditCard: {
                number: paymentInfo['cardNumber'],
                expirationDate: paymentInfo['expDate'],
                cvv: paymentInfo['ccv'],
              }
            }
          }, function (err, response) {
            if (err) {
              console.error(err);
              return;
            }

            Vue.http.post('/payments/braintree', {
              customerName: paymentInfo['customerName'],
              tel: paymentInfo['tel'],
              currency: paymentInfo['currency'],
              amount: paymentInfo['amount'],
              nonce: response.creditCards[0].nonce,
            }, {
              headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken}
            })
              .then((response) => {
                console.log(response);
              }, (error) => {
                console.error(error);
              });

          });
        });
      }
    }
  }
</script>
