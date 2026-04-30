<template>

	<div class="card">
		<div>
			<h4 style="float: left;">Product List</h4>

			<div class="btn-group" role="group" aria-label="Third group" style="float: right;">
				<router-link class="btn btn-primary" :to="{ name: 'add' }">Add</router-link>
				<router-link class="btn btn-primary" :to="{ name: 'Profile' }">Profile</router-link>
				<router-link class="btn btn-primary" :to="{ name: 'DailyEntryForm' }">Daily Efficiency
					Report</router-link>
				<router-link class="btn btn-primary" :to="{ name: 'LeftSidebar' }">Chat</router-link>
				<router-link class="btn btn-primary" :to="{ name: 'others' }">others</router-link>
			</div>
		</div>
		<div class="card-body">

			<div class="table-responsive-sm">
				<table class="table caption-top">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Product Name</th>
							<th scope="col">Item</th>
							<th scope="col">Price</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="item in products" :key="item.id">
							<th scope="row">{{ item.id }}</th>
							<td>{{ item.name }}</td>
							<td>{{ item.item }}</td>
							<td>{{ item.price }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<script>
import axios from 'axios';
export default {
	data() {
		return {
			products: []
		}
	},

	mounted() {
		this.ProductList();
	},
	methods: {
		ProductList() {
			axios.get('/api/products')
				.then(res => {
					this.products = res.data;
				})
				.catch(err => {
					console.log(err);
				});
		}

	}
}

</script>
