/** @format */

import React, { PureComponent } from "react";
import { View, Dimensions } from "react-native";
import { ImageCache, TouchableScale } from "@components";
const { width } = Dimensions.get("window");

export default class BannerImage extends PureComponent {
  render() {
    const { viewAll, config } = this.props;
    const column = config.column || 1;
    const height = config.height || (width/column) - 20;
    const resizeMode = config.imageMode || 'contain'

    return (
      <TouchableScale onPress={viewAll}>
        <View
          activeOpacity={1}
          style={styles.imageBannerView(column, height)}>
          <ImageCache uri={config.imageBanner} style={styles.imageBanner(resizeMode)} />
        </View>
      </TouchableScale>
    );
  }
}

const styles={
  imageBannerView: (column, height) => ({
      width: (width/column) - 20,
      height: height,
      borderRadius: 3,
      marginLeft: 10,
      marginRight: 10,
      marginTop: column == 1 ? 10 : 0,
      overflow: 'hidden',
      marginBottom: 10,
  }),
  imageBanner: (resizeMode) => ({
    flex: 1,
    resizeMode: resizeMode,
  }),
}