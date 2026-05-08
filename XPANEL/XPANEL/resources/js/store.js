import { defineStore } from "pinia";

export const cartShop = defineStore("xPanel", {
    state: () => ({
        items: JSON.parse(localStorage.getItem("cartList")) || [],
    }),
    getters: {
        totalPrice() {
            let total = 0;
            this.items.forEach((item) => {
                total += item.price * item.quantity;
            });
            return total;
        },
    },
    actions: {
        addItem(item) {
            if (this.items.length > 0) {
                // Check if item already exists, if so, increase quantity
                let index = this.items.findIndex((i) => i.id == item.id);
                if (index != -1) {
                    this.items[index].quantity += 1;
                } else {
                    const result = {
                        id: item.id,
                        name: item.name,
                        quantity: item.quantity,
                        price: item.price.price,
                        features: item.features,
                    };
                    this.items.push(result);
                }
            } else {
                const result = {
                    id: item.id,
                    name: item.name,
                    quantity: item.quantity,
                    price: item.price.price,
                    features: item.features,
                };
                this.items.push(result);
            }

            // Save to local storage
            localStorage.setItem("cartList", JSON.stringify(this.items));
        },
        removeItem(id) {
            let index = this.items.findIndex((i) => i.id == id);
            this.items.splice(index, 1);
            localStorage.setItem("cartList", JSON.stringify(this.items));
        },
        clearCart() {
            this.items = [];
            localStorage.setItem("cartList", JSON.stringify(this.items));
        },
    },
});
