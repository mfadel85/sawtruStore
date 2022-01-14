/** @format */

import React, {PureComponent} from "react";
import {Text, TouchableOpacity} from "react-native";
import {Styles, Images, withTheme} from "@common";
import {ProductPrice, ImageCache, WishListIcon} from "@components";
import {getProductImage} from "@app/Omni";
import css from "./style";

class ThreeColumn extends PureComponent {
  render() {
    const {
      viewPost,
      title,
      product,
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
      <TouchableOpacity activeOpacity={0.9} style={css.panelThree} onPress={viewPost}>
        <ImageCache uri={imageURI} style={css.imagePanelThree}/>
        <Text numberOfLines={2} style={[css.nameThree, {color: text}]}>
          {title}
        </Text>
        <ProductPrice product={product} hideDisCount/>
        <WishListIcon product={product}/>
      </TouchableOpacity>
    );
  }
}

export default withTheme(ThreeColumn)