/** @format */

// @flow
/**
 * Created by InspireUI on 19/02/2017.
 */
import React, { PureComponent } from "react";
import PropTypes from "prop-types";
import { View } from "react-native";
import { connect } from "react-redux";
import { Constants, withTheme, AppConfig } from "@common";
import { HorizonList, ModalLayout, PostList } from "@components";
import styles from "./styles";

class Home extends PureComponent {

  constructor(props) {
    super(props);
    this.state = {
      count: 0
    };
  }
  static propTypes = {
    fetchAllCountries: PropTypes.func.isRequired,
    layoutHome: PropTypes.any,
    onViewProductScreen: PropTypes.func,
    onShowAll: PropTypes.func,
    showCategoriesScreen: PropTypes.func,
  };

  componentDidMount() {
    const { fetchAllCountries, fetchCategories, isConnected } = this.props;
    if (isConnected) {
      fetchAllCountries();
      fetchCategories();
    }
  }

  render() {
    const {
      layoutHome,
      onViewProductScreen,
      showCategoriesScreen,
      onShowAll,
      theme: {
        colors: { background },
      },
    } = this.props;

    const isHorizontal = layoutHome == Constants.Layout.horizon;
    console.log('Horizontal ',this.state.count, isHorizontal);
    return (
      <View style={[styles.container, { backgroundColor: background }]}>
        {isHorizontal && (
          <HorizonList
            onShowAll={onShowAll}
            onViewProductScreen={onViewProductScreen}
            showCategoriesScreen={showCategoriesScreen}
          />
        )}

        {!isHorizontal && (
          <PostList onViewProductScreen={onViewProductScreen} />
        )}
        <ModalLayout />
      </View>
    );
  }
}

const mapStateToProps = ({ user, products, countries, netInfo }) => ({
  user,
  layoutHome: products.layoutHome,
  countries,
  isConnected: netInfo.isConnected,
});

function mergeProps(stateProps, dispatchProps, ownProps) {
  const { dispatch } = dispatchProps;
  const CountryRedux = require("@redux/CountryRedux");
  const { actions } = require("@redux/CategoryRedux");

  return {
    ...ownProps,
    ...stateProps,
    fetchCategories: () => actions.fetchCategories(dispatch),
    fetchAllCountries: () => CountryRedux.actions.fetchAllCountries(dispatch),
  };
}

export default withTheme(
  connect(
    mapStateToProps,
    undefined,
    mergeProps
  )(Home)
);
