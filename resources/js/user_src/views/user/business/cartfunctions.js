import store from "@@@/store";
import axios from "@axios";

export default {
    goback() {
        this.$router.go(-1);
    },
    single_restaurant() {
        if (localStorage.getItem("webuserData")) {
            let loggeduser = JSON.parse(localStorage.getItem("webuserData"));
            var user_id = loggeduser.id;
        } else {
            var user_id = 0;
        }
        this.$http
            .get(
                "/single_restaurant/" +
                    this.product.restaurant_id +
                    "/" +
                    user_id
            )
            .then((res) => {
                this.rows = res.data.restaurants;
            });
    },
    addCustomizedProduct(selected) {
        localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
        if (Array.isArray(selected.food_image)) {
            var food_image_a = selected.food_image[0];
        } else {
            var food_image_a = selected.food_image;
        }
        let id = selected.id,
            restaurant = selected.restaurant,
            isveg = selected.isveg,
            restaurant_name = selected.restaurant_name,
            restaurant_address = selected.restaurant_address,
            restaurant_image = selected.restaurant_image,
            packaging_charge = selected.packaging_charge,
            taxperc = selected.taxperc,
            name = selected.name,
            food_image = food_image_a,
            cart = JSON.parse(localStorage.getItem("cart"));
        let price = parseFloat(selected.price);
        let selectedSize = undefined;
        let selectedSizeName = undefined;
        let selectedSizePrice = "";
        if (
            document.querySelector(
                'input[name="sizesOfItem_' + id + '"]:checked'
            )
        ) {
            selectedSize = document.querySelector(
                'input[name="sizesOfItem_' + id + '"]:checked'
            ).value;
            selectedSizeName = document
                .querySelector('input[name="sizesOfItem_' + id + '"]:checked')
                .getAttribute("data-name");
            selectedSizePrice = document
                .querySelector('input[name="sizesOfItem_' + id + '"]:checked')
                .getAttribute("data-price");
        }
        selectedSize = selectedSize == undefined ? "" : selectedSize;
        selectedSizePrice = isNaN(selectedSizePrice) ? 0 : selectedSizePrice;
        if (selectedSizePrice > 0) {
            price = selectedSizePrice;
        }
        let selectedAddons = [];
        let selectedAddonsName = [];
        let selectedAddonsPrice = 0;
        let selectedAddonsqty = [];
        let selectedGroups = [];
        if (selected.food_quantity.length) {
            for (var i = 0; i < selected.food_quantity.length; i++) {
                if (selected.food_quantity[i].id == selectedSize) {
                    if (selected.food_quantity[i].groups) {
                        for (
                            var j = 0;
                            j < selected.food_quantity[i].groups.length;
                            j++
                        ) {
                            if (
                                selected.food_quantity[i].groups[j]
                                    .allow_multiple == 1
                            ) {
                                if (
                                    selected.food_quantity[i].groups[j]
                                        .mandatory == 1 &&
                                    document.getElementById(
                                        "hidden_" +
                                            selected.food_quantity[i].groups[j]
                                                .id
                                    ).value <
                                        selected.food_quantity[i].groups[j].min
                                ) {
                                    this.alert_text =
                                        "Minimum " +
                                        selected.food_quantity[i].groups[j]
                                            .min +
                                        " Items has to be Selected in " +
                                        selected.food_quantity[i].groups[j]
                                            .name;
                                    this.showDismissibleAlert = true;
                                    return false;
                                }
                                for (
                                    var k = 0;
                                    k <
                                    selected.food_quantity[i].groups[j]
                                        .group_addons.length;
                                    k++
                                ) {
                                    var added = document.getElementById(
                                        "size_qty_" +
                                            selected.food_quantity[i].groups[j]
                                                .id +
                                            "_" +
                                            selected.food_quantity[i].groups[j]
                                                .group_addons[k].id
                                    ).value;
                                    if (added > 0) {
                                        selectedAddons.push(
                                            document
                                                .getElementById(
                                                    "size_qty_" +
                                                        selected.food_quantity[
                                                            i
                                                        ].groups[j].id +
                                                        "_" +
                                                        selected.food_quantity[
                                                            i
                                                        ].groups[j]
                                                            .group_addons[k].id
                                                )
                                                .getAttribute("data-value")
                                        );
                                        selectedAddonsName.push(
                                            document
                                                .getElementById(
                                                    "size_qty_" +
                                                        selected.food_quantity[
                                                            i
                                                        ].groups[j].id +
                                                        "_" +
                                                        selected.food_quantity[
                                                            i
                                                        ].groups[j]
                                                            .group_addons[k].id
                                                )
                                                .getAttribute("data-name")
                                        );
                                        selectedAddonsPrice +=
                                            parseFloat(
                                                document
                                                    .getElementById(
                                                        "size_qty_" +
                                                            selected
                                                                .food_quantity[
                                                                i
                                                            ].groups[j].id +
                                                            "_" +
                                                            selected
                                                                .food_quantity[
                                                                i
                                                            ].groups[j]
                                                                .group_addons[k]
                                                                .id
                                                    )
                                                    .getAttribute("data-price")
                                            ) *
                                            document.getElementById(
                                                "size_qty_" +
                                                    selected.food_quantity[i]
                                                        .groups[j].id +
                                                    "_" +
                                                    selected.food_quantity[i]
                                                        .groups[j].group_addons[
                                                        k
                                                    ].id
                                            ).value;
                                        selectedAddonsqty.push(
                                            document.getElementById(
                                                "size_qty_" +
                                                    selected.food_quantity[i]
                                                        .groups[j].id +
                                                    "_" +
                                                    selected.food_quantity[i]
                                                        .groups[j].group_addons[
                                                        k
                                                    ].id
                                            ).value
                                        );
                                        let ob = {
                                            group_id:
                                                selected.food_quantity[i]
                                                    .groups[j].id,
                                            add_ons: document
                                                .getElementById(
                                                    "size_qty_" +
                                                        selected.food_quantity[
                                                            i
                                                        ].groups[j].id +
                                                        "_" +
                                                        selected.food_quantity[
                                                            i
                                                        ].groups[j]
                                                            .group_addons[k].id
                                                )
                                                .getAttribute("data-value"),
                                            quantity: document.getElementById(
                                                "size_qty_" +
                                                    selected.food_quantity[i]
                                                        .groups[j].id +
                                                    "_" +
                                                    selected.food_quantity[i]
                                                        .groups[j].group_addons[
                                                        k
                                                    ].id
                                            ).value,
                                        };
                                        selectedGroups.push(ob);
                                    }
                                }
                            } else {
                                if (
                                    selected.food_quantity[i].groups[j]
                                        .mandatory == 1 &&
                                    document.querySelectorAll(
                                        'input[name="faddon' +
                                            selected.food_quantity[i].groups[j]
                                                .id +
                                            '"]:checked'
                                    ).length <
                                        selected.food_quantity[i].groups[j].min
                                ) {
                                    this.alert_text =
                                        "Minimum " +
                                        selected.food_quantity[i].groups[j]
                                            .min +
                                        " Items has to be Selected in " +
                                        selected.food_quantity[i].groups[j]
                                            .name;
                                    this.showDismissibleAlert = true;
                                    return false;
                                }
                                document
                                    .querySelectorAll(
                                        'input[name="faddon' +
                                            selected.food_quantity[i].groups[j]
                                                .id +
                                            '"]:checked'
                                    )
                                    .forEach(function (index, value) {
                                        selectedAddons.push(index.value);
                                        selectedAddonsName.push(
                                            index.getAttribute("data-name")
                                        );
                                        selectedAddonsPrice += parseFloat(
                                            index.getAttribute("data-price")
                                        );
                                        selectedAddonsqty.push(1);
                                        let ob = {
                                            group_id:
                                                selected.food_quantity[i]
                                                    .groups[j].id,
                                            add_ons: index.value,
                                            quantity: 1,
                                        };
                                        selectedGroups.push(ob);
                                    });
                            }
                        }
                    }
                    if (selected.food_quantity[i].add_ons) {
                        document
                            .querySelectorAll(
                                'input[name="saddonsOfItem_' +
                                    selected.food_quantity[i].id +
                                    '"]:checked'
                            )
                            .forEach(function (index, value) {
                                selectedAddons.push(index.value);
                                selectedAddonsName.push(
                                    index.getAttribute("data-name")
                                );
                                selectedAddonsPrice += parseFloat(
                                    index.getAttribute("data-price")
                                );
                                selectedAddonsqty.push(1);
                            });
                    }
                }
            }
        }
        if (selected.groups && selected.groups.length) {
            for (var i = 0; i < selected.groups.length; i++) {
                if (selected.groups[i].allow_multiple == 1) {
                    if (
                        selected.groups[i].mandatory == 1 &&
                        document.getElementById(
                            "hidden_" + selected.groups[i].id
                        ).value < selected.groups[i].min
                    ) {
                        this.alert_text =
                            "Minimum " +
                            selected.groups[i].min +
                            " Items has to be Selected in " +
                            selected.groups[i].name;
                        this.showDismissibleAlert = true;
                        return false;
                    }
                    for (
                        var k = 0;
                        k < selected.groups[i].group_addons.length;
                        k++
                    ) {
                        var added = document.getElementById(
                            "size_qty_" +
                                selected.groups[i].id +
                                "_" +
                                selected.groups[i].group_addons[k].id
                        ).value;
                        if (added > 0) {
                            selectedAddons.push(
                                document
                                    .getElementById(
                                        "size_qty_" +
                                            selected.groups[i].id +
                                            "_" +
                                            selected.groups[i].group_addons[k]
                                                .id
                                    )
                                    .getAttribute("data-value")
                            );
                            selectedAddonsName.push(
                                document
                                    .getElementById(
                                        "size_qty_" +
                                            selected.groups[i].id +
                                            "_" +
                                            selected.groups[i].group_addons[k]
                                                .id
                                    )
                                    .getAttribute("data-name")
                            );
                            selectedAddonsPrice +=
                                parseFloat(
                                    document
                                        .getElementById(
                                            "size_qty_" +
                                                selected.groups[i].id +
                                                "_" +
                                                selected.groups[i].group_addons[
                                                    k
                                                ].id
                                        )
                                        .getAttribute("data-price")
                                ) *
                                document.getElementById(
                                    "size_qty_" +
                                        selected.groups[i].id +
                                        "_" +
                                        selected.groups[i].group_addons[k].id
                                ).value;
                            selectedAddonsqty.push(
                                document.getElementById(
                                    "size_qty_" +
                                        selected.groups[i].id +
                                        "_" +
                                        selected.groups[i].group_addons[k].id
                                ).value
                            );
                            let ob = {
                                group_id: selected.groups[i].id,
                                add_ons: document
                                    .getElementById(
                                        "size_qty_" +
                                            selected.groups[i].id +
                                            "_" +
                                            selected.groups[i].group_addons[k]
                                                .id
                                    )
                                    .getAttribute("data-value"),
                                quantity: document.getElementById(
                                    "size_qty_" +
                                        selected.groups[i].id +
                                        "_" +
                                        selected.groups[i].group_addons[k].id
                                ).value,
                            };
                            selectedGroups.push(ob);
                        }
                    }
                } else {
                    if (
                        selected.groups[i].mandatory == 1 &&
                        document.querySelectorAll(
                            'input[name="faddon' +
                                selected.groups[i].id +
                                '"]:checked'
                        ).length < selected.groups[i].min
                    ) {
                        this.alert_text =
                            "Minimum " +
                            selected.groups[i].min +
                            " Items has to be Selected in " +
                            selected.groups[i].name;
                        this.showDismissibleAlert = true;
                        return false;
                    }
                    document
                        .querySelectorAll(
                            'input[name="faddon' +
                                selected.groups[i].id +
                                '"]:checked'
                        )
                        .forEach(function (index, value) {
                            selectedAddons.push(index.value);
                            selectedAddonsName.push(
                                index.getAttribute("data-name")
                            );
                            selectedAddonsPrice += parseFloat(
                                index.getAttribute("data-price")
                            );
                            selectedAddonsqty.push(1);
                            let ob = {
                                group_id: selected.groups[i].id,
                                add_ons: index.value,
                                quantity: 1,
                            };
                            selectedGroups.push(ob);
                        });
                }
            }
        }
        if (selected.add_ons && selected.add_ons.length) {
            document
                .querySelectorAll(
                    'input[name="addonsOfItem_' + selected.id + '"]:checked'
                )
                .forEach(function (index, value) {
                    selectedAddons.push(index.value);
                    selectedAddonsName.push(index.getAttribute("data-name"));
                    selectedAddonsPrice += parseFloat(
                        index.getAttribute("data-price")
                    );
                    selectedAddonsqty.push(1);
                });
        }
        let to_display = [];
        if (selectedAddonsName && selectedAddonsName.length) {
            let to = "";
            for (var i = 0; i < selectedAddonsName.length; i++) {
                to = selectedAddonsName[i] + " x " + selectedAddonsqty[i];
                to_display.push(to);
            }
        }
        let selectedAddonsString = selectedAddons.join(",");
        selectedAddonsName = selectedAddonsName.join(", ");
        selectedAddonsqty = selectedAddonsqty.join(",");
        to_display = to_display.join(",");
        selectedAddonsPrice = parseFloat(selectedAddonsPrice);

        // *** ‘Add to Cart’ is not restaurant-based, so the conditions mentioned below have been removed. ***

        // if (cart.length && cart[0].restaurant != restaurant) {
        //     let ok = confirm('The items previously will be deleted');
        //     if (ok) {
        //         cart = [];
        //         store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', store.state['deliware_cart'].cartItemsCount - 1)
        //     }
        //     else return;
        // }
        if (cart.length != 0) {
            if (
                typeof selectedSizeName == "undefined" &&
                selectedAddonsName != ""
            ) {
                let food_isPresent = cart.some(function (el) {
                    return el.id === id;
                });
                let food_addon_isPresent = cart.some(function (el) {
                    return el.addonsName === selectedAddonsName;
                });
                if (food_isPresent === true && food_addon_isPresent === true) {
                    cart = localStorage.getItem("cart");
                    cart = JSON.parse(cart);
                    for (var i = 0; i < cart.length; i++) {
                        if (
                            cart[i].addonsName === selectedAddonsName &&
                            cart[i].id === id &&
                            cart[i].to_display === to_display
                        ) {
                            cart[i].quantity = cart[i].quantity + 1;
                            break;
                        }
                    }
                } else {
                    cart.push({
                        id: id,
                        name: name,
                        isveg: isveg,
                        price: parseFloat(parseFloat(price).toFixed(2)),
                        quantity: 1,
                        restaurant: restaurant,
                        addons: selectedAddonsString,
                        sizes: selectedSize,
                        addonsName: selectedAddonsName,
                        addonsPrice: selectedAddonsPrice,
                        addonsqty: selectedAddonsqty,
                        selectedGroups: selectedGroups,
                        to_display: to_display,
                        sizeName: selectedSizeName,
                        restaurant_name: restaurant_name,
                        restaurant_address: restaurant_address,
                        restaurant_image: restaurant_image,
                        restaurant_packaging_charge: packaging_charge,
                        sizePrice: selectedSizePrice,
                        taxperc: taxperc,
                        food_image: food_image,
                        pharmacy_id: this.pharmacy_id,
                    });
                }
            } else {
                if (selectedSizeName != "" && selectedAddonsName != "") {
                    let food_isPresent = cart.some(function (el) {
                        return el.id === id;
                    });
                    let food_addon_isPresent = cart.some(function (el) {
                        return el.addonsName === selectedAddonsName;
                    });
                    let food_size_isPresent = cart.some(function (el) {
                        return el.sizeName === selectedSizeName;
                    });
                    if (
                        food_isPresent === true &&
                        food_addon_isPresent === true &&
                        food_size_isPresent == true
                    ) {
                        cart = localStorage.getItem("cart");
                        cart = JSON.parse(cart);
                        for (var i = 0; i < cart.length; i++) {
                            let temp_loop_count = i + 1;
                            if (
                                cart[i].addonsName === selectedAddonsName &&
                                cart[i].sizeName === selectedSizeName &&
                                cart[i].id === id &&
                                cart[i].to_display === to_display
                            ) {
                                cart[i].quantity = cart[i].quantity + 1;
                                break;
                            }
                            if (temp_loop_count == cart.length) {
                                cart.push({
                                    id: id,
                                    name: name,
                                    isveg: isveg,
                                    price: parseFloat(
                                        parseFloat(price).toFixed(2)
                                    ),
                                    quantity: 1,
                                    restaurant: restaurant,
                                    addons: selectedAddonsString,
                                    sizes: selectedSize,
                                    addonsName: selectedAddonsName,
                                    addonsPrice: selectedAddonsPrice,
                                    addonsqty: selectedAddonsqty,
                                    selectedGroups: selectedGroups,
                                    to_display: to_display,
                                    sizeName: selectedSizeName,
                                    restaurant_name: restaurant_name,
                                    restaurant_address: restaurant_address,
                                    restaurant_image: restaurant_image,
                                    restaurant_packaging_charge:
                                        packaging_charge,
                                    sizePrice: selectedSizePrice,
                                    taxperc: taxperc,
                                    food_image: food_image,
                                    pharmacy_id: this.pharmacy_id,
                                });
                                break;
                            }
                        }
                    } else {
                        cart.push({
                            id: id,
                            name: name,
                            isveg: isveg,
                            price: parseFloat(parseFloat(price).toFixed(2)),
                            quantity: 1,
                            restaurant: restaurant,
                            addons: selectedAddonsString,
                            sizes: selectedSize,
                            addonsName: selectedAddonsName,
                            addonsPrice: selectedAddonsPrice,
                            addonsqty: selectedAddonsqty,
                            selectedGroups: selectedGroups,
                            to_display: to_display,
                            sizeName: selectedSizeName,
                            restaurant_name: restaurant_name,
                            restaurant_address: restaurant_address,
                            restaurant_image: restaurant_image,
                            restaurant_packaging_charge: packaging_charge,
                            sizePrice: selectedSizePrice,
                            taxperc: taxperc,
                            food_image: food_image,
                            pharmacy_id: this.pharmacy_id,
                        });
                    }
                }
                if (selectedSizeName != "" && selectedAddonsName === "") {
                    let food_isPresent = cart.some(function (el) {
                        return el.id === id;
                    });
                    let food_size_isPresent = cart.some(function (el) {
                        return el.sizeName === selectedSizeName;
                    });
                    if (
                        food_isPresent === true &&
                        food_size_isPresent == true
                    ) {
                        cart = localStorage.getItem("cart");
                        cart = JSON.parse(cart);
                        for (var i = 0; i < cart.length; i++) {
                            let temp_loop_count = i + 1;
                            if (
                                cart[i].addonsName === selectedAddonsName &&
                                cart[i].sizeName === selectedSizeName &&
                                cart[i].id === id
                            ) {
                                cart[i].quantity = cart[i].quantity + 1;
                                break;
                            }
                            if (temp_loop_count == cart.length) {
                                cart.push({
                                    id: id,
                                    name: name,
                                    isveg: isveg,
                                    price: parseFloat(
                                        parseFloat(price).toFixed(2)
                                    ),
                                    quantity: 1,
                                    restaurant: restaurant,
                                    addons: selectedAddonsString,
                                    sizes: selectedSize,
                                    addonsName: selectedAddonsName,
                                    addonsPrice: selectedAddonsPrice,
                                    addonsqty: selectedAddonsqty,
                                    selectedGroups: selectedGroups,
                                    to_display: to_display,
                                    sizeName: selectedSizeName,
                                    restaurant_name: restaurant_name,
                                    restaurant_address: restaurant_address,
                                    restaurant_image: restaurant_image,
                                    restaurant_packaging_charge:
                                        packaging_charge,
                                    sizePrice: selectedSizePrice,
                                    taxperc: taxperc,
                                    food_image: food_image,
                                    pharmacy_id: this.pharmacy_id,
                                });
                                break;
                            }
                        }
                    } else {
                        cart.push({
                            id: id,
                            name: name,
                            isveg: isveg,
                            price: parseFloat(parseFloat(price).toFixed(2)),
                            quantity: 1,
                            restaurant: restaurant,
                            addons: selectedAddonsString,
                            sizes: selectedSize,
                            addonsName: selectedAddonsName,
                            addonsPrice: selectedAddonsPrice,
                            addonsqty: selectedAddonsqty,
                            selectedGroups: selectedGroups,
                            to_display: to_display,
                            sizeName: selectedSizeName,
                            restaurant_name: restaurant_name,
                            restaurant_address: restaurant_address,
                            restaurant_image: restaurant_image,
                            restaurant_packaging_charge: packaging_charge,
                            sizePrice: selectedSizePrice,
                            taxperc: taxperc,
                            food_image: food_image,
                            pharmacy_id: this.pharmacy_id,
                        });
                    }
                }
            }
        } else {
            cart.push({
                id: id,
                name: name,
                isveg: isveg,
                price: parseFloat(parseFloat(price).toFixed(2)),
                quantity: 1,
                restaurant: restaurant,
                addons: selectedAddonsString,
                sizes: selectedSize,
                addonsName: selectedAddonsName,
                addonsPrice: selectedAddonsPrice,
                addonsqty: selectedAddonsqty,
                selectedGroups: selectedGroups,
                to_display: to_display,
                sizeName: selectedSizeName,
                restaurant_name: restaurant_name,
                restaurant_address: restaurant_address,
                restaurant_image: restaurant_image,
                restaurant_packaging_charge: packaging_charge,
                sizePrice: selectedSizePrice,
                taxperc: taxperc,
                food_image: food_image,
                pharmacy_id: this.pharmacy_id,
            });
        }
        localStorage.setItem("cart", JSON.stringify(cart));
        let cartn = JSON.parse(localStorage.getItem("cart"));
        store.commit("deliware_cart/UPDATE_CART_ITEMS_COUNT", cartn.length);
        store.commit("deliware_cart/UPDATE_CART_ITEMS", cartn);
        this.loadcart();
    },
    calc_delivery_charge(total_price, rest_id) {
        return new Promise(function (resolve, reject) {
            var lat = localStorage.getItem("latitude");
            var lng = localStorage.getItem("longitude");
            axios
                .get(
                    "/calc_delivery_charge/" +
                        rest_id +
                        "/" +
                        lat +
                        "/" +
                        lng +
                        "/" +
                        total_price
                )
                .then(
                    (response) => {
                        var result = response.data.delivery_charge;
                        console.log("Processing Request");
                        resolve(result);
                    },
                    (error) => {
                        reject(error);
                    }
                );
        });
    },
    async loadcart() {
        setTimeout(async () => {
            localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
            let cart = JSON.parse(localStorage.getItem("cart"));
            var lat = localStorage.getItem("latitude");
            var lng = localStorage.getItem("longitude");
            // if (localStorage.getItem('webuserData')) {
            //   this.$http.get('/get_checkout_details/' + cart[0].restaurant + '/' + lat + '/' + lng + '/' + this.userData.id)
            //     .then(res => {
            //       localStorage.setItem('DISTANCE', res.data.distance);
            //     })
            // }
            var tips = 0;
            let wallet = localStorage.getItem("WALLET");
            let total_price = 0;
            let total_item = 0;
            let tot_tax = 0;
            let is_tax = localStorage.getItem("IS_TAX_INCLUSIVE");
            let tot_amt = 0;
            let item_amount_total = 0;
            let restaurant_packaging_charge = 0;
            let packaging_charge = 0;
            if (packaging_charge == 0) {
                packaging_charge =
                    cart.length !== 0
                        ? cart[0].restaurant_packaging_charge || 0
                        : 0;
                //  JSON.parse(localStorage.getItem("RES_PACK_CHARGE"));
            }
            if (cart.length === 0) {
            } else {
                let result = Object.values(
                    cart.reduce((a, { id, quantity }) => {
                        let key = `${id}_${quantity}`;
                        a[key] = a[key] || { id, quantity, count: 0 };
                        a[key].count++;
                        return a;
                    }, {})
                );
                var resArr = [];
                cart.filter(function (item) {
                    var i = resArr.findIndex((x) => x.id == item.id);
                    if (i <= -1) {
                        resArr.push(item);
                    }
                    return null;
                });
                for (var i = 0; i < resArr.length; i++) {
                    var qq = 0;
                    var rr = 0;
                    for (var j = 0; j < result.length; j++) {
                        if (result[j].id == resArr[i].id) {
                            qq = qq + result[j].quantity;
                            rr = rr + result[j].count;
                        }
                    }
                    var elms3 = document.querySelectorAll(
                        '[id="food_qty_' + resArr[i].id + 'r"]'
                    );
                    for (var k = 0; k < elms3.length; k++) {
                        if (qq > rr) {
                            elms3[k].value = qq;
                        } else {
                            elms3[k].value = rr;
                        }
                    }
                }

                cart.forEach(function (item, i) {
                    if (
                        document.querySelectorAll(
                            '[id="food_control_' + item.id + 'r"]'
                        )
                    ) {
                        var elms = document.querySelectorAll(
                            '[id="food_control_' + item.id + 'r"]'
                        );
                        for (var i = 0; i < elms.length; i++) {
                            elms[i].style.display = "block";
                        }
                        var elms2 = document.querySelectorAll(
                            '[id="food_add_' + item.id + 'r"]'
                        );
                        for (var i = 0; i < elms2.length; i++) {
                            elms2[i].style.display = "none";
                        }

                        // Existing below was command Reference ************
                        var elms3 = document.querySelectorAll(
                            '[id="food_qty_' + item.id + 'r"]'
                        );
                        for (var i = 0; i < elms3.length; i++) {
                            elms3[i].value = item.quantity;
                        }
                        // Existing below was command Reference ************
                        if (document.getElementById("card" + item.id)) {
                            var element = document.getElementById(
                                "card" + item.id
                            );
                            element.classList.add("incart");
                        }
                    }
                    total_price += parseFloat(
                        (
                            item.quantity *
                            (item.price + item.addonsPrice)
                        ).toFixed(2)
                    );
                    total_item++;
                    let thisAmount = parseFloat(
                        (
                            (item.price + item.addonsPrice) *
                            item.quantity
                        ).toFixed(2)
                    );
                    tot_tax += parseFloat(
                        (thisAmount / 100) * (item.taxperc || 0)
                    );
                    localStorage.setItem("tot_tax", tot_tax);

                    // if (is_tax == 0) {
                    //     tot_amt = total_price + tot_tax;
                    // } else {
                    tot_amt = total_price;
                    // }
                    item_amount_total += thisAmount;
                });

                restaurant_packaging_charge = parseFloat(
                    (item_amount_total / 100) * packaging_charge
                );
                var dc = 1;
                if (dc == 1) {
                    var delivery_charge_calc = await this.calc_delivery_charge(
                        tot_amt,
                        cart[0].restaurant
                    );
                    console.log("st 1");
                } else {
                    var delivery_charge_calc = 0;
                    var tips = 0;
                }
                var offer_discount = 0;
                var offer_code = 0;
                if (localStorage.getItem("discount_value")) {
                    offer_discount = parseFloat(
                        localStorage.getItem("discount_value")
                    );
                    offer_code = localStorage.getItem("coupon");
                } else {
                    offer_discount = 0;
                    offer_code = "";
                }
                total_price =
                    tot_amt === 0
                        ? tot_amt
                        : (
                              tot_amt -
                              parseFloat(offer_discount) +
                              tot_tax +
                              restaurant_packaging_charge +
                              delivery_charge_calc
                          ).toFixed(2);
                localStorage.setItem("tot_amt", total_price);
                // var bill = {'item_total':tot_amt,'packaging_charge':restaurant_packaging_charge,'delivery_charge':delivery_charge_calc,'discount':0,'bill':total_price};
                if (total_price < parseFloat(wallet)) {
                    wallet = total_price;
                    total_price = 0;
                } else {
                    total_price = total_price - wallet;
                }
                var bill = {
                    item_total: tot_amt,
                    packaging_charge: restaurant_packaging_charge,
                    delivery_charge: delivery_charge_calc,
                    discount: offer_discount,
                    bill: total_price,
                    tips: tips,
                    dc: dc,
                    tax: tot_tax,
                    offer_code: offer_code,
                    wallet: wallet,
                };
                localStorage.setItem("bill", bill);
                store.commit("deliware_cart/UPDATE_CART_TOTAL", bill);
                this.value = !this.value;
                // this.loadcoupon()
            }
        }, 300);
    },
    open_plus(data, isopen) {
        console.log("Res Data", data);
        let selected = {};
        if (
            !data.food_quantity.length &&
            !data.add_ons.length &&
            !data.groups.length
        ) {
            selected.id = data.id;
            selected.restaurant = data.restaurant;
            selected.isveg = data.isveg;
            selected.restaurant_name = data.restaurant_name;
            selected.restaurant_address = data.restaurant_address;
            selected.restaurant_image = data.restaurant_image;
            selected.packaging_charge = data.restaurant_packaging_charge;
            // selected.taxperc = parseFloat(data.taxperc);
            selected.taxperc = parseFloat(data.item_tax);
            selected.price = parseFloat(data.price);
            selected.add_ons = data.add_ons;
            selected.food_quantity = data.food_quantity;
            selected.food_image = data.image[0];
            selected.name = data.name;
            selected.groups = data.groups;
            this.check_cart(selected, isopen);
            return;
        }
        var s_fq = "";
        this.tabIndex = 0;
        this.showDismissibleAlert = false;
        this.dont_show = false;
        this.itemid = data.food_id;
        if (this.$refs["image-modal"]) {
            this.$refs["image-modal"].show();
        }
        this.modal_image = data.image;
        this.modal_title = data.name;
        this.add_ons = data.add_ons;
        this.food_quantity = data.food_quantity;
        if (this.food_quantity.length) {
            for (var i = 0; i < this.food_quantity.length; i++) {
                if (this.food_quantity[i].is_default == 1) {
                    this.s_fq = this.food_quantity[i].id;
                    if (
                        this.food_quantity[i].addon_group == null &&
                        this.food_quantity[i].addons_id == null &&
                        !data.groups.length
                    ) {
                        this.dont_show = true;
                    }
                }
            }
            if (data.groups.length || data.add_ons.length) {
                this.dont_show = false;
            }
        }
        var modal_groups = data.groups;
        var modal_desc = data.description;
        selected.id = data.id;
        selected.restaurant = data.restaurant;
        selected.isveg = data.isveg;
        selected.restaurant_name = data.restaurant_name;
        selected.restaurant_address = data.restaurant_address;
        selected.restaurant_image = data.restaurant_image;
        selected.packaging_charge = data.restaurant_packaging_charge;
        // selected.taxperc = parseFloat(data.taxperc);
        selected.taxperc = parseFloat(data.item_tax);
        selected.price = parseFloat(data.price);
        selected.add_ons = data.add_ons;
        selected.food_quantity = data.food_quantity;
        selected.food_image = data.image[0];
        selected.name = data.name;
        selected.groups = data.groups;
        this.check_cart(selected, isopen);
    },
    check_cart(selected, isOpen) {
        console.log("Res Data Full", selected);
        console.log("Res Data Name", selected.restaurant_name);
        console.log("Res Data", selected.restaurant);

        localStorage.setItem("cart", localStorage.getItem("cart") || "[]");

        if (!selected.restaurant) {
            return this.$swal({
                title: "No Nearby Restaurant",
                text: "You can't add items to the cart because there is no restaurant nearby.",
                icon: "warning",
                confirmButtonText: "OK",
                customClass: {
                    confirmButton: "btn btn-primary",
                },
                buttonsStyling: false,
            });
        }

        if (selected.restaurant && isOpen == 0) {
          return this.$swal({
                title: "Restaurant Closed",
                text: "Sorry, you can't add items right now. The restaurant is closed.",
                icon: "warning",
                confirmButtonText: "OK",
                customClass: {
                    confirmButton: "btn btn-primary",
                },
                buttonsStyling: false,
            });
        }
        console.log("Entered56");

        // *** ‘Add to Cart’ is not restaurant-based, so the conditions mentioned below have been removed. ***

        // let restaurant = selected.restaurant,
        // cart = JSON.parse(localStorage.getItem("cart"));
        // if (cart.length && cart[0].restaurant != restaurant) {
        //     this.$swal({
        //         title: 'Are you sure?',
        //         text: "The previously selected items will be deleted!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'Yes, delete it!',
        //         customClass: {
        //             confirmButton: 'btn btn-primary',
        //             cancelButton: 'btn btn-outline-danger ml-1',
        //         },
        //         buttonsStyling: false,
        //     }).then(result => {
        //         if (result.value) {
        //             cart = [];
        //             localStorage.setItem("cart", JSON.stringify(cart));
        //             store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', store.state['deliware_cart'].cartItemsCount - 1)
        //             this.addCustomizedProduct(selected)
        //         }
        //     })
        // } else {
        this.addCustomizedProduct(selected);
        // }
    },
    removefromcart(
        price,
        item_tax,
        name,
        food_id,
        is_veg,
        add_ons,
        food_quantity,
        food_image
    ) {
        localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
        let cart = JSON.parse(localStorage.getItem("cart"));
        let foodIndex = cart.findIndex(function (food, index) {
            return food.id == food_id;
        });
        if (foodIndex !== -1) {
            if (cart[foodIndex].quantity > 0) cart[foodIndex].quantity--;
            if (cart[foodIndex].quantity <= 0) {
                cart.splice(foodIndex, 1);
                var elms = document.querySelectorAll(
                    '[id="food_control_' + food_id + 'r"]'
                );
                for (var i = 0; i < elms.length; i++) {
                    elms[i].style.display = "none";
                }
                var elms2 = document.querySelectorAll(
                    '[id="food_add_' + food_id + 'r"]'
                );
                for (var i = 0; i < elms2.length; i++) {
                    elms2[i].style.display = "inline-block";
                }
                // document.getElementById('food_control_' + id +'r').style.display = 'none';
                // document.getElementById('food_add_' + id +'r').style.display = 'inline-block';
            }
            if (document.getElementById("card" + food_id)) {
                var element = document.getElementById("card" + food_id);
                element.classList.remove("incart");
            }
        }
        localStorage.setItem("cart", JSON.stringify(cart));
        let cartn = JSON.parse(localStorage.getItem("cart"));
        store.commit("deliware_cart/UPDATE_CART_ITEMS_COUNT", cartn.length);
        store.commit("deliware_cart/UPDATE_CART_ITEMS", cartn);
        localStorage.removeItem("discount_value");
        localStorage.removeItem("coupon");
        this.loadcart();
    },
    add_sizeaddon(data, data2) {
        var dummy = document.getElementById("hidden_" + data.id).value;
        if (dummy < data.max && data.mandatory == 1) {
            document.getElementById(
                "size_control_" + data.id + "_" + data2.id
            ).style.display = "block";
            document.getElementById(
                "size_addon_" + data.id + "_" + data2.id
            ).style.display = "none";
            document.getElementById("hidden_" + data.id).value = ++dummy;
            var count = document.getElementById(
                "size_qty_" + data.id + "_" + data2.id
            ).value;
            var inc = ++count;
            document.getElementById(
                "size_qty_" + data.id + "_" + data2.id
            ).value = inc;
        } else if (data.mandatory == 0) {
            document.getElementById(
                "size_control_" + data.id + "_" + data2.id
            ).style.display = "block";
            document.getElementById(
                "size_addon_" + data.id + "_" + data2.id
            ).style.display = "none";
            document.getElementById("hidden_" + data.id).value = ++dummy;
            var count = document.getElementById(
                "size_qty_" + data.id + "_" + data2.id
            ).value;
            var inc = ++count;
            document.getElementById(
                "size_qty_" + data.id + "_" + data2.id
            ).value = inc;
        } else {
            this.alert_text =
                "Maximum " + data.max + " can be Selected in " + data.name;
            this.showDismissibleAlert = true;
        }
    },
    decrease_size(data, data2) {
        var dummy = document.getElementById("hidden_" + data.id).value;
        document.getElementById("hidden_" + data.id).value = --dummy;
        var count = document.getElementById(
            "size_qty_" + data.id + "_" + data2.id
        ).value;
        var dec = --count;
        if (dec == 0) {
            document.getElementById(
                "size_control_" + data.id + "_" + data2.id
            ).style.display = "none";
            document.getElementById(
                "size_addon_" + data.id + "_" + data2.id
            ).style.display = "block";
            document.getElementById(
                "size_qty_" + data.id + "_" + data2.id
            ).value = dec;
        } else {
            document.getElementById(
                "size_qty_" + data.id + "_" + data2.id
            ).value = dec;
        }
    },
};
