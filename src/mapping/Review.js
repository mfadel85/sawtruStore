import moment from 'moment'

export default class Review {
  constructor(post) {
    const {
      author,
      text,
      date_added,
      rating,
    } = post

    try {
      this.id = `${Math.random()}`
      this.name = author
      this.avatar = "https://minervastrategies.com/wp-content/uploads/2016/03/default-avatar.jpg"
      this.review = text
      this.date_created = moment(date_added)
      this.rating = rating
      this.parent = 0
      this.likes = 0,
        this.isLiked = false

    } catch (e) {
      console.error(e.message)
    }
  }
}
