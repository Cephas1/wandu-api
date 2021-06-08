<template>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantite</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="basket in baskets" :key="basket.id">
                    <td>{{ basket.article.name }}</td>
                    <td>{{ basket.article.price_4 }}</td>
                    <td><input  @change="calculateTotal()" v-model="basket.quantity" type="number"></td>
                    <td>{{ basket.quantity * basket.article.price_4 }}</td>
                    <td><a @click="removeBasket(basket.id)" href="#" class="btn btn-danger btn-social mx-2"> - </a></td>
                </tr>
            </tbody>
        </table><hr/>
        <div>
            <h4>Transport : 1000 FCFA</h4>
            <h4>Total HT : {{ tht }} </h4>
        </div>
        <div class="col-lg-8 mx-auto text-center">
            <button @click="removeAllBasket()" class="btn btn-danger btn-success">Vider le panier</button>
            <button @click="commandProducs()" class="btn btn-success">Commander</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["baskets"],
        data(){
            return {
                basket_item: {},
                tht: 0
            }
        },
        methods: {
            calculateTotal: () => {
                baskets.forEach(basket => {
                    this.tht += basket.quantity * basket.article.price_4
                });
            },
            commandProducs: () => {
                const url = '/basket'
                const option = {
                    method: 'GET'
                }
                fetch(url, option)
                    .then(response => response.json())
                    .then(response => {
                    if (response.removed === 1) {
                        baskets = []
                    }
                })
            },
            removeBasket: (basket_id) => {
                const url = `/basket/remove/${basket_id}`
                const option = {
                    method: 'GET'
                }
                fetch(url, option)
                    .then(response => response.json())
                    .then(response => {
                        if (response.removed === 1) {
                            baskets.filter(el => el.id === basket_id)
                            calculateTotal()
                        }
                    })
            },
            removeAllBasket: (basket_id) => {
                const url = `/basket/removeall`
                const option = {
                    method: 'GET'
                }
                fetch(url, option)
                    .then(response => response.json())
                    .then(response => {
                        if (response.removed === 1) {
                            baskets = []
                        }
                    })
            }
        }
    }
</script>