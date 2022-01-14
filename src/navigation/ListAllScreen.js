/** @format */

import React, { Component } from "react";

import { Images, Styles, withTheme } from "@common";
import { ProductList } from "@components";
import { Back } from "./IconNav";

@withTheme
export default class ListAllScreen extends Component {
  static navigationOptions = ({ navigation }) => {
    const dark = navigation.getParam("dark", false);

    return {
      headerLeft: Back(navigation, Images.icons.backs, dark),

      headerStyle: Styles.Common.toolbarFloat,
      headerTransparent: true,
    };
  };

  componentWillMount() {
    const {
      theme: { dark },
    } = this.props;

    this.props.navigation.setParams({
      dark,
    });
  }

  componentWillReceiveProps(nextProps) {
    if (this.props.theme.dark !== nextProps.theme.dark) {
      const {
        theme: { dark },
      } = nextProps;
      this.props.navigation.setParams({
        dark,
      });
    }
  }

  render() {
    const { state, navigate } = this.props.navigation;
    const params = state.params;
    console.log("test me man params",params,navigate);

    return (
      <ProductList
        headerImage={
          params.config.image && params.config.image.length > 0
            ? { uri: params.config.image }
            : null
        }
        config={params.config}
        page={1}
        navigation={this.props.navigation}
        index={params.index}
        onViewProductScreen={(item) => navigate("DetailScreen", item)}
      />
    );
  }
}
