
import store from '../store'

export default (to, from, next) => {
    // check if user Auth continu request
    // else convert to login page. .
    // it is like middleware for specific component
    if (store.getters.isAuth) {
        next()
    } else {
        next('/login')
    }
}
