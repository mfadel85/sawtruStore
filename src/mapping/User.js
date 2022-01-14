export default class User {
  constructor(post) {
    const {
      customer_id,
      firstname,
      lastname,
      email
    } = post

    try {
      this.id = customer_id
      this.first_name = firstname
      this.last_name = lastname
      this.email = email
      this.billing = {
        first_name: firstname,
        last_name: lastname
      }
    } catch (e) {
      console.error(e.message)
    }
  }
}
