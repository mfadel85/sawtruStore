import { AppConfig } from '../common'
import axios from 'axios'
import { Category, Product, User, ShippingMethod, PaymentMethod, Address, MyOrder, 
    Review } from '@mapping'
import moment from 'moment'
import { warn } from "../Omni";
import _ from 'lodash'

export const getUserInfo = () => {
    return new Promise((resolve, reject) => {
        axios.get(AppConfig.Opencart.url + "/index.php?route=extension/mstore/account")
            .then(({ status, data }) => {
                resolve(status == 200 && data.data ? data.data : null)
            })
            .catch(reject)
    })
}

export const getCategories = () => {
    return new Promise((resolve, reject) => {
        axios.get(AppConfig.Opencart.url + "/index.php?route=extension/mstore/category&limit=100")
            .then(({ status, data }) => {
                if (status == 200 && data) {
                    resolve(_.map(data.data, (item) => new Category(item)))
                } else {
                    resolve([])
                }
            })
            .catch(reject)
    })
}

export const getAllProducts = (per_page, page, order, orderby) => {
    return new Promise((resolve, reject) => {
        var url = AppConfig.Opencart.url + `/index.php?route=extension/mstore/product&page=${page}&limit=${per_page}&lang=en`
        axios.get(url)
            .then(({ status, data }) => {
                if (status == 200 && data.data) {
                    resolve(_.map(data.data, (item) => new Product(item)))
                } else {
                    resolve([])
                }
            })
            .catch(reject)
    })
}

export const productsByCategoryId = (categoryId,
    per_page,
    page,
    filters) => {
    return new Promise((resolve, reject) => {
        var url = AppConfig.Opencart.url + `/index.php?route=extension/mstore/product&page=${page}&limit=${per_page}&lang=en`
        if (filters.category || categoryId) {
            url += `&category=${filters.category ? filters.category : categoryId}`
        }
        axios.get(url)
            .then(({ status, data }) => {
                console.log("products are ",data);

                if (status == 200 && data.data) {
                    resolve(_.map(data.data, (item) => new Product(item)))
                } else {
                    resolve([])
                }
            })
            .catch(reject)
    })
}

export const productsByCategoryTag = (categoryId,
    tagId,
    per_page,
    page) => {
    return new Promise((resolve, reject) => {
        var url = null
        if (categoryId) {
            url = AppConfig.Opencart.url + `/index.php?route=extension/mstore/product&page=${page}&limit=${per_page}&category=${categoryId}&lang=en`
        } else {
            url = AppConfig.Opencart.url + `/index.php?route=extension/mstore/product&page=${page}&limit=${per_page}&tag=${tagId}&lang=en`
        }
        axios.get(url)
            .then(({ status, data }) => {
                if (status == 200 && data.data) {
                    resolve(_.map(data.data, (item) => new Product(item)))
                } else {
                    resolve([])
                }
            })
            .catch(reject)
    })
}

export const productsByName = (name,
    per_page,
    page,
    filters) => {
    return new Promise((resolve, reject) => {
        var url = AppConfig.Opencart.url + `/index.php?route=extension/mstore/product&page=${page}&limit=${per_page}&search=${name}&lang=en`
        if (filters.category) {
            url += `&category=${filters.category}`
        }
        if (filters.max_price) {
            url += `&max_price=${filters.max_price}`
        }
        axios.get(url)
            .then(({ status, data }) => {
                if (status == 200 && data.data) {
                    resolve(_.map(data.data, (item) => new Product(item)))
                } else {
                    resolve([])
                }
            })
            .catch(reject)
    })
}

export const register = ({ telephone, email, firstName, lastName, password }) => {
    return new Promise((resolve, reject) => {
        axios.post(AppConfig.Opencart.url + "/index.php?route=extension/mstore/account/register", {
            firstname: firstName,
            lastname: lastName,
            email,
            "agree": "1",
            password,
            confirm: password,
            telephone
        })
            .then(({ status, data }) => {
                logout()
                resolve(new User(data.data))
            })
            .catch((err) => {
                const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                reject(message)
            })
    })
}

export const login = (username, password) => {
    return new Promise((resolve, reject) => {
        logout()
        axios.post(AppConfig.Opencart.url + "/index.php?route=extension/mstore/account/login", {
            email: username, password
        })
            .then((res) => {
                resolve({ user: new User(res.data.data), token: "No token" })
            })
            .catch((err) => {
                const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                reject(message)
            })
    })
}
export const fbLogin = (email) => {
    console.log("Have we passed this?Let's see!! 1");

    return new Promise((resolve,reject) => {
        console.log("Have we passed this?Let's see!! 2");

        logout();
        console.log("Have we passed this?Let's see!! 2.1");

        axios.post(AppConfig.Opencart.url + "/index.php?route=extension/mstore/account/googleLogin",{
            email:email
        })
        .then((res)=> {
            console.log('res is ',res.data)
            console.log("Have we passed this?Let's see!! 3");
            resolve({user:new User(res.data.data),token:"No token"})
            console.log("Have we passed this?Let's see!! 4");

        })
        .catch((err) => {
                console.log("Have we passed this?Let's see!! not");

                const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                reject(message)
            })
    });    
}
export const googleLogin = (email) => {
    console.log("Have we passed this?Let's see!! 1",email);

    return new Promise((resolve,reject) => {
        console.log("Have we passed this?Let's see!! 2");

        logout();
        console.log("Have we passed this?Let's see!! 2.1");

        axios.post(AppConfig.Opencart.url + "/index.php?route=extension/mstore/account/googleLogin",{
            email:email
        })
        .then((res)=> {
            console.log('res is ',res.data)
            console.log("Have we passed this?Let's see!! 3");
            resolve({user:new User(res.data.data),token:"No token"})
            console.log("Have we passed this?Let's see!! 4");

        })
        .catch((err) => {
                console.log("Have we passed this?Let's see!! not");

                const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                reject(message)
            })
    });
}
export const logout = () => {
    axios.get(AppConfig.Opencart.url + "/index.php?route=extension/mstore/account")
}

export const emptyCart = () => {
    return new Promise((resolve, reject) => {
        axios.delete(AppConfig.Opencart.url + "/index.php?route=extension/mstore/cart/emptyCart")
            .then(({ status, data }) => {
                resolve(data)
            })
            .catch((err) => {
                const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                reject(message)
            })
    })
}

export const addItemsToCart = (carts) => {
    return new Promise((resolve, reject) => {
        const params = _.map(carts, (item) => ({ product_id: item.product.id, quantity: item.quantity }))
        axios.post(AppConfig.Opencart.url + "/index.php?route=extension/mstore/cart/add", params)
            .then(({ status, data }) => {
                resolve(data)
            })
            .catch((err) => {
                const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                reject(message)
            })
    })
}

export const setShippingAddress = (address) => {
    return new Promise((resolve, reject) => {
        axios.post(AppConfig.Opencart.url + "/index.php?route=extension/mstore/shipping_address/save", address)
            .then(({ status, data }) => {
                resolve(data)
            })
            .catch((err) => {
                const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                reject(message)
            })
    })
}

export const getShippingMethod = (address, token) => {
    return new Promise((resolve, reject) => {
        setShippingAddress(new Address(address))
            .then(() => {
                axios.get(AppConfig.Opencart.url + "/index.php?route=extension/mstore/shipping_method")
                    .then(({ status, data }) => {
                        var items = []
                        Object.values(data.data.shipping_methods).forEach((item) => {
                            var shippingMethod = new ShippingMethod(item)
                            items.push(shippingMethod)
                        })
                        resolve(items)
                    })
                    .catch((err) => {
                        const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                        reject(message)
                    })
            })
            .catch(reject)
    })
}

export const setPaymentAddress = (address) => {
    return new Promise((resolve, reject) => {
        axios.post(AppConfig.Opencart.url + "/index.php?route=extension/mstore/payment_address/save", address)
            .then(({ status, data }) => {
                resolve(data)
            })
            .catch((err) => {
                const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                reject(message)
            })
    })
}

export const getPayments = (address, token) => {
    return new Promise((resolve, reject) => {
        setPaymentAddress(new Address(address))
            .then(() => {
                axios.get(AppConfig.Opencart.url + "/index.php?route=extension/mstore/payment_method")
                    .then(({ status, data }) => {
                        var items = []
                        Object.values(data.data.payment_methods).forEach((item) => {
                            var paymentMethod = new PaymentMethod(item)
                            items.push(paymentMethod)
                        })
                        resolve(items)
                    })
                    .catch((err) => {
                        const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                        reject(message)
                    })
            })
            .catch(reject)
    })
}

export const setShippingMethod = (shipping_method) => {
    return new Promise((resolve, reject) => {
        axios.post(AppConfig.Opencart.url + "/index.php?route=extension/mstore/shipping_method/save", { shipping_method, comment: "string" })
            .then(({ status, data }) => {
                resolve(data)
            })
            .catch((err) => {
                const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                reject(message)
            })
    })
}

export const setPaymentMethod = (payment_method) => {
    return new Promise((resolve, reject) => {
        axios.post(AppConfig.Opencart.url + "/index.php?route=extension/mstore/payment_method/save", { payment_method, comment: "string", "agree": "1" })
            .then(({ status, data }) => {
                resolve(data)
            })
            .catch((err) => {
                const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                reject(message)
            })
    })
}

export const createNewOrder = async (payload, onFinish, onError) => {
    try {
        await axios.get(AppConfig.Opencart.url + "/index.php?route=extension/mstore/shipping_method")
        await setShippingMethod(payload.shipping_method)
        await axios.get(AppConfig.Opencart.url + "/index.php?route=extension/mstore/payment_method")
        await setPaymentMethod(payload.payment_method)
        await axios.post(AppConfig.Opencart.url + "/index.php?route=extension/mstore/order/confirm", {})

        onFinish()
    } catch (error) {
        onError(error)
    }
}

export const ordersByCustomerId = (customer_id, per_page, page) => {
    return new Promise((resolve, reject) => {
        axios.get(AppConfig.Opencart.url + `/index.php?route=extension/mstore/order/orders&limit=${per_page}&page=${page}`)
            .then(({ status, data }) => {
                if (status == 200 && data.data) {
                    var items = []
                    data.data.forEach((item) => {
                        items.push(new MyOrder(item))
                    })
                    resolve(items)
                } else {
                    resolve([])
                }
            })
            .catch(reject)
    })
}

export const reviewsByProductId = (product_id) => {
    return new Promise((resolve, reject) => {
        axios.get(AppConfig.Opencart.url + `/index.php?route=extension/mstore/review&id=${product_id}`)
            .then(({ status, data }) => {
                if (status == 200 && data.data) {
                    var items = []
                    data.data.forEach((item) => {
                        items.push(new Review(item))
                    })
                    resolve(items)
                } else {
                    resolve([])
                }
            })
            .catch(reject)
    })
}

export const postReview = (product_id, name, text, rating) => {
    return new Promise((resolve, reject) => {
        axios.post(AppConfig.Opencart.url + `/index.php?route=extension/mstore/review&id=${product_id}`, { name, text, rating })
            .then(resolve)
            .catch((err) => {
                const message = err.response && err.response.data.error ? err.response.data.error[0] : "Server don't response correctly"
                reject(message)
            })
    })
}