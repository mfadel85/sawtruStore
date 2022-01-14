/** @format */

import { PixelRatio } from "react-native";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { decode } from "html-entities";
import truncate from "lodash/truncate";
import URI from "urijs";
import currencyFormatter from "currency-formatter";
import { Constants, Languages, Images, Config, warn } from "@common";

export default class Tools {
  /**
   * refresh the tab bar & read later page
   */
  static getImage(data, imageSize) {
    if (typeof data === "undefined" || data == null) {
      return Constants.PlaceHolder;
    }
    if (typeof imageSize === "undefined") {
      imageSize = "medium";
    }

    const getImageSize = (mediaDetail) => {
      let imageURL = "";
      if (typeof mediaDetail.sizes !== "undefined") {
        if (typeof mediaDetail.sizes[imageSize] !== "undefined") {
          imageURL = mediaDetail.sizes[imageSize].source_url;
        }

        if (imageURL == "" && typeof mediaDetail.sizes.medium !== "undefined") {
          imageURL = mediaDetail.sizes.medium.source_url;
        }

        if (imageURL == "" && typeof mediaDetail.sizes.full !== "undefined") {
          imageURL = mediaDetail.sizes.full.source_url;
        }
      }

      if (typeof data.better_featured_image != null) {
        imageURL = data.better_featured_image.source_url;
      }

      return imageURL;
    };

    let imageURL =
      typeof data.better_featured_image !== "undefined" &&
        data.better_featured_image != null
        ? data.better_featured_image.source_url
        : Constants.PlaceHolderURL;

    if (
      typeof data.better_featured_image !== "undefined" &&
      data.better_featured_image !== null
    ) {
      if (typeof data.better_featured_image.media_details !== "undefined") {
        imageURL = getImageSize(data.better_featured_image.media_details);
      }
    }

    if (imageURL == "") {
      return Constants.PlaceHolderURL;
    }

    return imageURL;
  }

  static getProductImage = (uri, containerWidth) => {
    // Enhance number if you want to fetch a better quality image (may affect performance
    const DPI_NUMBER = 0.5; // change this to 1 for high quality image

    if (!Config.ProductSize.enable) {
      return uri;
    }

    if (typeof uri !== "string") {
      return Images.PlaceHolderURL;
    }

    // parse uri into parts
    const index = uri.lastIndexOf(".");
    let editedURI = uri.slice(0, index);
    const defaultType = uri.slice(index);

    const pixelWidth = PixelRatio.getPixelSizeForLayoutSize(containerWidth);

    switch (true) {
      case pixelWidth * DPI_NUMBER < 300:
        editedURI = `${editedURI}-small${defaultType}`;
        break;
      case pixelWidth * DPI_NUMBER < 600:
        editedURI = `${editedURI}-medium${defaultType}`;
        break;
      case pixelWidth * DPI_NUMBER < 1400:
        editedURI = `${editedURI}-large${defaultType}`;
        break;
      default:
        editedURI += defaultType;
    }
    return editedURI;
  };

  /**
   * get image depend on variation and product
   */
  static getImageVariation(product, variation) {
    return product.images.length > 0 ? product.images[0].src : ""
    const defaultImage = Tools.getProductImage(product.image.src, 100);

    return variation
      ? variation.image.id === 0
        ? defaultImage
        : Tools.getProductImage(variation.image.src, 100)
      : defaultImage;
  }

  static getDescription(description, limit) {
    //console.log("descr reis: ",description);

    if (typeof limit === "undefined") {
      limit = 50;
    }

    if (typeof description === "undefined") {
      return "";
    }
    //console.log('allhtmlent',Html5Entities);
    let desc = description.replace("<p>", "");
    desc = truncate(desc, { length: limit, separator: " " });
    var mecod = decode(desc);
    //console.log("mecod is: ",mecod);

    return decode(desc);

  }

  static getLinkVideo(content) {
    const regExp = /^.*((www.youtube.com\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\??v?=?))([^#&\?\/\ ]*).*/;
    let embedId = "";
    let youtubeUrl = "";

    URI.withinString(content, (url) => {
      const match = url.match(regExp);
      if (match && match[7].length === 11) {
        embedId = match[7];
        youtubeUrl = `www.youtube.com/embed/${embedId}`;
      }
    });
    return youtubeUrl;
  }

  static async getFontSizePostDetail() {
    const data = await AsyncStorage.getItem("@setting_fontSize");
    if (typeof data !== "undefined") {
      return parseInt(data);
    }
    return Constants.fontText.size;
  }

  /**
   * getName user
   * @user
   */
  static getName = (user) => {
    if (user != null) {
      if (
        typeof user.last_name !== "undefined" ||
        typeof user.first_name !== "undefined"
      ) {
        const first = user.first_name != null ? user.first_name : "";
        const last = user.last_name != null ? user.last_name : "";
        return `${first} ${last}`;
      } else if (typeof user.name !== "undefined" && user.name != null) {
        return user.name;
      }
      return Languages.Guest;
    }
    return Languages.Guest;
  };

  /**
   * getAvatar
   * @user
   */
  static getAvatar = (user) => {
    if (user) {
      if (user.avatar_url) {
        return {
          uri: user.avatar_url,
        };
      } else if (user.picture) {
        return {
          uri: user.picture.data.url,
        };
      }
      return Images.defaultAvatar;
    }

    return Images.defaultAvatar;
  };
  // format currency
  static getCurrecyFormatted = (price) => {
    let formatedPrice = "";
    if (price && price !== "") {
      formatedPrice = currencyFormatter.format(price, {
        ...Config.DefaultCurrency,
      });
    }

    return formatedPrice;
  };
  /**
   * Calculate price included tax amount
   */
  static getPriceIncluedTaxAmount = (product, variation, noFormat) => {
    if (!product) return null;

    const productPrice =
      variation && variation.price !== ""
        ? variation.price
        : product.price !== ""
          ? product.price
          : product.regular_price;

    if (
      product.tax_status === "taxable" &&
      product.tax_class &&
      product.tax_class !== ""
    ) {
      const taxAmount = Number(product.tax_class);
      const includedPrice = productPrice * ((100 + taxAmount) / 100);
      warn(includedPrice);
      return noFormat
        ? includedPrice
        : Tools.getCurrecyFormatted(includedPrice);
    }

    // warn(productPrice);
    return noFormat ? productPrice : Tools.getCurrecyFormatted(productPrice);
  };
}
