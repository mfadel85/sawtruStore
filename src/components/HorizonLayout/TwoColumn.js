/** @format */

import React, {PureComponent} from "react";
import {Text, TouchableOpacity} from "react-native";
import {Images, Styles, withTheme} from "@common";
import {WishListIcon, ImageCache, ProductPrice} from "@components";
import {getProductImage} from "@app/Omni";
import css from "./style";

class TwoColumn extends PureComponent {
  render() {
    const {
      title,
      product,
      viewPost,
      theme: {
        colors: {
          text
        }
      }
    } = this.props;
    const imageURI = typeof product.images[0] !== "undefined"
      ? getProductImage(product.images[0].src, Styles.width)
      : Images.PlaceHolderURL;

    return (
      <TouchableOpacity activeOpacity={0.9} style={css.panelTwo} onPress={viewPost}>
        <ImageCache uri={imageURI} style={css.imagePanelTwo}/>
        <Text numberOfLines={2} style={[css.nameTwo, {color: text}]}>
          {title}
        </Text>
        <ProductPrice product={product} hideDisCount/>
        <WishListIcon product={product}/>
      </TouchableOpacity>
    );
  }
}

export default withTheme(TwoColumn)