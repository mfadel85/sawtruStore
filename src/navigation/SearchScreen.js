/** @format */

import React, { PureComponent } from "react";
import { Search } from "@components";

export default class SearchScreen extends PureComponent {
  static navigationOptions = ({ navigation }) => ({
    title: "Search",
    header: null,
    // tabBarVisible: false,

    tabBarLabel: null,
  });

  render() {
    const { navigate, goBack } = this.props.navigation;

    return (
      <Search
        onBack={goBack}
        onViewProductScreen={(product) => navigate("DetailScreen", product)}
        navigation={this.props.navigation}
        onFilter={(onSearch) => navigate("FiltersScreen", { onSearch })}
      />
    );
  }
}
