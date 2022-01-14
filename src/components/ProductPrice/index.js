/** @format */

import React, { PureComponent } from "react";
import PropTypes from "prop-types";
import { View, Text } from "react-native";
import { Color, withTheme, Tools } from "@common";
import styles from "./styles";

class ProductPrice extends PureComponent {
  static propTypes = {
    product: PropTypes.object,
    hideDisCount: PropTypes.bool,
    style: PropTypes.any,
  };

  render() {
    const {
      product,
      hideDisCount,
      style,
      fontStyle,
      theme: {
        colors: { text },
      },
    } = this.props;
    return (
      <View style={[styles.price_wrapper, style && style]}>
        <Text
          style={[
            styles.text_list,
            styles.price,
            {
              color: Color.blackTextSecondary,
            },
            { color: text },
            fontStyle && fontStyle,
          ]}>
          {`${Tools.getPriceIncluedTaxAmount(product)} `}
        </Text>
        
        {product.on_sale && <Text style={[styles.text_list, styles.sale_price, { color: text}, fontStyle && fontStyle,]}>
          {product.on_sale
            ? Tools.getCurrecyFormatted(product.regular_price)
            : ""}
        </Text>}

        {hideDisCount ? (
          <View />
        ) : !product.on_sale ? (
          <View />
        ) : (
          <View style={styles.saleWrap}>
            <Text style={[styles.text_list, styles.sale_off, { color: text }, fontStyle && fontStyle,]}>
              {`-${(
                (1 - Number(product.price) / Number(product.regular_price)) *
                100
              ).toFixed(0)}%`}
            </Text>
          </View>
        )}
      </View>
    );
  }
}

export default withTheme(ProductPrice);
