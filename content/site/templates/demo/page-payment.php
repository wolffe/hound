<?php include get_theme_directory('header.php'); ?>

<div class="page-wrapper">
    <div class="page-wrapper-full">
        <h2>[@title]</h2>

        [@content]

        <div id="paypal-button-container"></div>
        <script src="https://www.paypalobjects.com/api/checkout.js"></script>
        <script>
        paypal.Button.render({

        env: 'production', // sandbox | production

        style: {
          layout: 'horizontal',  // horizontal | vertical
          size:   'medium',    // medium | large | responsive
          shape:  'rect',      // pill | rect
          color:  'blue',       // gold | blue | silver | white | black
          tagline: false
        },

        // Specify allowed and disallowed funding sources
        //
        // Options:
        // - paypal.FUNDING.CARD
        // - paypal.FUNDING.CREDIT
        // - paypal.FUNDING.ELV
        funding: {
          allowed: [
            paypal.FUNDING.CARD,
          ],
          disallowed: [
              paypal.FUNDING.CREDIT
          ]
        },

        client: {
          sandbox: 'AffQm8UqLxXQyTORi0pnRz1t6QBmaW-7rDdcXuVL1kcVtR54G-h_JjUXFqZ8zQQYX33cnuCoplOtP-Wv',
          production: 'AY9fckicAY8f-IDyNsztFPfJyvX7uHGnvoGMOecWwXmcf2Vs9OZ1M93J4rsNjfx87Wih6v4-w7lsKM3S'
        },

        payment: function (data, actions) {
          return actions.payment.create({
            payment: {
              transactions: [
                {
                  amount: {
                    total: '0.01',
                    currency: 'USD'
                  }
                }
              ]
            },
          experience: {
                    input_fields: {
                        no_shipping: 1
                    }
                }
          });
        },

        onAuthorize: function (data, actions) {
          return actions.payment.execute()
            .then(function () {
              window.alert('Payment Complete!');
            });
        }
        }, '#paypal-button-container');
        </script>

        <hr>
        <form action="your-server-side-code" method="POST">
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="pk_test_TYooMQauvdEDq54NiTphI7jx"
    data-amount="999"
    data-name="Stripe.com"
    data-description="Widget"
    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
    data-locale="auto"
    data-email="someemail@somedomain.com"
    data-label="Pay using Stripe"
    data-currency="EUR"
    data-zip-code="true">
  </script>
</form>
    </div>
</div>

<?php include get_theme_directory('footer.php'); ?>
