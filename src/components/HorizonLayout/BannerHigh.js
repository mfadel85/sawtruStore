/** @format */

import React, { PureComponent } from "react";
import { View, Text } from "react-native";
import { Images, Styles, Tools } from "@common";
import { ImageCache, WishListIcon, TouchableScale } from "@components";
import { getProductImage } from "@app/Omni";
import { LinearGradient } from "@expo";
import css from "./style";

export default class BannerLarge extends PureComponent {
  render() {
    const { viewPost, title, product } = this.props;
    const imageURI =
      typeof product.images[0] !== "undefined"
        ? getProductImage(product.images[0].src, Styles.width * 0.7)
        : Images.PlaceHolderURL;

    const productPrice = `${Tools.getPriceIncluedTaxAmount(product)} `;

    const productPriceSale = product.on_sale
      ? `${Tools.getCurrecyFormatted(product.regular_price)} `
      : null;

    return (
      <TouchableScale onPress={viewPost} style={css.bannerHighShadow}>
        <View activeOpacity={1} style={css.bannerHighView}>
          <ImageCache uri={imageURI} style={css.bannerHighImage} />

          <LinearGradient
            colors={["rgba(244, 244, 246, 0)", "rgba(244, 244, 246, 0.9)"]}
            style={css.bannerOverlay}>
            <Text style={css.bannerHighTitle}>{title}</Text>
            <View style={css.priceView}>
              <Text style={[css.price]}>{productPrice}</Text>
              <Text style={[css.price, product.on_sale && css.sale_price]}>
                {productPriceSale}
              </Text>
            </View>
          </LinearGradient>

          <WishListIcon product={product} />
        </View>
      </TouchableScale>
    );
  }
}
