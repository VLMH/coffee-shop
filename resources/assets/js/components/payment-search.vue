<template>
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="form-inline" v-on:submit.prevent="onSubmit">

        <h3><u>Search</u></h3>

        <input type="text" class="form-control input-lg" id="name" name="name" placeholder="Customer Name" v-model="search['name']" required>
        <input type="text" class="form-control input-lg" id="code" placeholder="Payment Code"v-model="search['code']" required>
        <button type="submit" class="btn btn-default btn-lg">Search</button>

      </form>
    </div>

    <div id="paymentModal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Payment</h4>
          </div>
          <div class="modal-body">
            <dl class="dl-horizontal">
              <span v-for="(data, key) in paymentDetails">
                <dt>{{ data['title'] }}</dt>
                <dd>{{ data['value'] }}</dd>
              </span>
            </dl>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="notFoundModal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Message</h4>
          </div>
          <div class="modal-body">
            <p>Record not found</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>
</template>

<script>
  export default {
    data() {
      return {
        search: {
          name: '',
          code: ''
        },
        paymentDetails: {
          customerName: {title: 'Customer Name', value: ''},
          tel: {title: 'Tel', value: ''},
          currency: {title: 'Currency', value: ''},
          amount: {title: 'Price', value: ''},
          code: {title: 'Payment Code', value: ''},
        }
      }
    },
    methods: {
      onSubmit: function() {
        var self = this;

        Vue.http.get('/payments/search', {
          params: {
            name: self.search['name'],
            code: self.search['code']
          }
        })
          .then((response) => {
            console.log(response);
            // var details = self.paymentDetails;
            // details['customerName']['value'] = response.body
          }, (error) => {
            console.error(error);
          });
      },
    }
  }
</script>
