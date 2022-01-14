/** @format */

"use strict";
import React, {Component} from "react";
import {Text, TouchableOpacity, Image} from "react-native";
import css from "./style";
import {Images, Styles, withTheme} from "@common";
import {ProductPrice, ImageCache, WishListIcon} from "@components";
import {getProductImage} from "@app/Omni";

class CardLayout extends Component {
  render() {
    const {
      product,
      title,
      viewPost,
      theme: {
        colors: {
          text
        }
      }
    } = this.props;

    const imageURI = typeof product.images[0] != "undefined"
      ? getProductImage(product.images[0].src, Styles.width)
      : Images.PlaceHolderURL;

    return (
      <TouchableOpacity activeOpacity={0.9} style={css.panelCard} onPress={viewPost}>
        <ImageCache uri={imageURI} style={css.imagePanelCard}/>
        <Text numberOfLines={2} style={[css.nameCard, {color: text}]}>
          {title}
        </Text>
        <ProductPrice product={product} hideDisCount/>
        <WishListIcon product={product}/>
      </TouchableOpacity>
    );
  }
}

export default withTheme(CardLayout)