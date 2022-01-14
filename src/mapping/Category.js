export default class Category {
  constructor(post) {
    const {
      id,
      name,
      seo_url,
      count,
      parent_id,
      image
    } = post

    try {
      this.id = id
      this.name = name
      this.slug = seo_url
      this.parent = parent_id
      this.description = ""
      this.display = true
      this.image = {
        id: "",
        src: image,
        title: "",
        alt: ""
      }
      this.menu_order = 0
      this.count = count

    } catch (e) {
      console.error(e.message)
    }
  }
}
