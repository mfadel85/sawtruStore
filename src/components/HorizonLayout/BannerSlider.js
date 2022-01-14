/** @format */

import React, {PureComponent} from "react";
import {ScrollView} from "react-native";
import {SlideItem} from "@components";

export default class BannerLarge extends PureComponent {
  render() {
    const {data, onViewPost} = this.props;

    return (
      <ScrollView>
        <SlideItem
          items={data}
          onViewPost={onViewPost}/>
      </ScrollView>
    );
  }
}
