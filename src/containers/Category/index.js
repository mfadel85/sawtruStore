/**
 * Created by InspireUI on 27/02/2017.
 *
 * @format
 */

import React, { Component } from "react";
import {
  View,
  RefreshControl,
  StyleSheet,
  FlatList,
  ScrollView,
  Animated,
} from "react-native";
import { connect } from "react-redux";
import { isObject } from "lodash";

import { Color, Languages, Styles, withTheme, Constants } from "@common";
import { Timer, toast, BlockTimer, log, warn } from "@app/Omni";
import LogoSpinner from "@components/LogoSpinner";
import Empty from "@components/Empty";
import { DisplayMode } from "@redux/CategoryRedux";
import FilterPicker from "@containers/FilterPicker";
import ProductRow from "./ProductRow";
import ControlBar from "./ControlBar";

const styles = StyleSheet.create({
  listView: {
    flexDirection: "row",
    flexWrap: "wrap",
    alignItems: "flex-start",
    paddingBottom: Styles.navBarHeight + 10,
  },
  container: {
    flexGrow: 1,
    backgroundColor: Color.background,
  },
});

class CategoryScreen extends Component {
  constructor(props) {
    super(props);
    this.state = {
      scrollY: new Animated.Value(0),
      loadingBuffer: true,
      modalVisible: false,
      displayControlBar: true,
    };
    this.pageNumber = 1;

    this.renderList = this.renderList.bind(this);
    this.renderRow = this.renderRow.bind(this);
    this.renderScrollComponent = this.renderScrollComponent.bind(this);
    this.onRowClickHandle = this.onRowClickHandle.bind(this);
    this.onEndReached = this.onEndReached.bind(this);
    this.onRefreshHandle = this.onRefreshHandle.bind(this);
    this.onListViewScroll = this.onListViewScroll.bind(this);

    this.openCategoryPicker = () => this.setState({ modalVisible: true });
    this.closeCategoryPicker = () => this.setState({ modalVisible: false });
  }

  shouldComponentUpdate(nextProps) {
    // const props = this.props;
    // const changeProduct =
    //   nextProps.products.list.length != props.products.list.length;
    // const changeCategory =
    //   props.selectedCategory.id !== nextProps.selectedCategory.id;

    return true;
  }

  componentDidMount() {
    Timer.setTimeout(() => this.setState({ loadingBuffer: false }), 1000);

    const {
      fetchProductsByCategoryId,
      clearProducts,
      selectedCategory,
    } = this.props;
    clearProducts();
    if (selectedCategory) {
      fetchProductsByCategoryId(selectedCategory.id, this.pageNumber++);
    }
  }

  componentWillReceiveProps(nextProps) {
    const props = this.props;
    const { error } = nextProps.products;
    if (error) toast(error);

    if (props.filters !== nextProps.filters) {
      const newFilters = this._getFilterId(nextProps.filters);

      this.pageNumber = 1;
      props.clearProducts();
      props.fetchProductsByCategoryId(null, this.pageNumber++, 20, newFilters);
    }

    if (props.selectedCategory != nextProps.selectedCategory) {
      this.pageNumber = 1;
      props.clearProducts();
      props.fetchProductsByCategoryId(nextProps.selectedCategory.id, this.pageNumber++);
    }

  }

  _getFilterId = (filters) => {
    let newFilters = {};
    Object.keys(filters).forEach((key) => {
      const value = filters[key];
      if (value) {
        newFilters = {
          ...newFilters,
          [key]: isObject(value) ? value.id || value.term_id : value,
        };
      }
    });
    // warn(newFilters);

    return newFilters;
  };

  render() {
    console.log("burdayim");
    const { modalVisible, loadingBuffer, displayControlBar } = this.state;
    const { products, selectedCategory, filters } = this.props;
    const {
      theme: {
        colors: { background },
      },
    } = this.props;

    if (!selectedCategory) return null;

    if (products.error) {
      return <Empty text={products.error} />;
    }

    if (loadingBuffer) {
      return <LogoSpinner fullStretch />;
    }

    const marginControlBar = this.state.scrollY.interpolate({
      inputRange: [-100, 0, 40, 50],
      outputRange: [0, 0, -50, -50],
    });

    const name =
      (filters && filters.category && filters.category.name) ||
      selectedCategory.name;

    return (
      <View style={[styles.container, { backgroundColor: background }]}>
        <Animated.View style={{ marginTop: marginControlBar }}>
          <ControlBar
            openCategoryPicker={this.openCategoryPicker}
            isVisible={displayControlBar}
            name={name}
          />
        </Animated.View>
        {this.renderList(products.list)}
        <FilterPicker
          closeModal={this.closeCategoryPicker}
          visible={modalVisible}
        />
      </View>
    );
  }

  renderList(data) {
    const { products, displayMode } = this.props;
    const isCardMode = displayMode == DisplayMode.CardMode;

    return (
      <FlatList
        data={data}
        renderItem={this.renderRow}
        enableEmptySections
        onEndReached={this.onEndReached}
        refreshControl={
          <RefreshControl
            refreshing={isCardMode ? false : products.isFetching}
            onRefresh={this.onRefreshHandle}
          />
        }
        contentContainerStyle={styles.listView}
        initialListSize={6}
        pageSize={2}
        renderScrollComponent={this.renderScrollComponent}
      />
    );
  }

  renderRow({item}) {
    const product = item
    const { displayMode } = this.props;
    const onPress = () => this.onRowClickHandle(product);
    const isInWishList =
      this.props.wishListItems.find((item) => item.product.id == product.id) !=
      undefined;

    return (
      <ProductRow
        product={product}
        onPress={onPress}
        displayMode={displayMode}
        isInWishList={isInWishList}
        addToWishList={this.addToWishList}
        removeWishListItem={this.removeWishListItem}
      />
    );
  }

  renderScrollComponent(props) {
    const { displayMode } = this.props;
    const mergeOnScroll = (event) => {
      props.onScroll(event);
      this.onListViewScroll(event);
    };

    if (displayMode == DisplayMode.CardMode) {
      return (
        <ScrollView
          horizontal
          pagingEnabled
          showsHorizontalScrollIndicator={false}
          props
          {...props}
          onScroll={mergeOnScroll}
        />
      );
    }

    return <ScrollView props {...props} onScroll={mergeOnScroll} />;
  }

  addToWishList = (product) => {
    this.props.addWishListItem(product);
  };

  removeWishListItem = (product) => {
    this.props.removeWishListItem(product);
  };

  onRowClickHandle(product) {
    BlockTimer.execute(() => {
      this.props.onViewProductScreen({ product });
    }, 500);
  }

  onEndReached() {
    const {
      products,
      fetchProductsByCategoryId,
      selectedCategory,
    } = this.props;
    if (!products.isFetching && products.stillFetch)
      fetchProductsByCategoryId(selectedCategory.id, this.pageNumber++);
  }

  onRefreshHandle() {
    const {
      fetchProductsByCategoryId,
      clearProducts,
      selectedCategory,
    } = this.props;
    this.pageNumber = 1;
    clearProducts();
    fetchProductsByCategoryId(selectedCategory.id, this.pageNumber++);
  }

  onListViewScroll(event: Object) {
    this.state.scrollY.setValue(event.nativeEvent.contentOffset.y);
  }
}

const mapStateToProps = (state) => {
  return {
    selectedCategory: state.categories.selectedCategory,
    netInfo: state.netInfo,
    displayMode: state.categories.displayMode,
    products: state.products,
    wishListItems: state.wishList.wishListItems,
    filters: state.filters,
  };
};

function mergeProps(stateProps, dispatchProps, ownProps) {
  const { netInfo } = stateProps;
  const { dispatch } = dispatchProps;
  const { actions } = require("@redux/ProductRedux");
  const WishListRedux = require("@redux/WishListRedux");
  return {
    ...ownProps,
    ...stateProps,
    fetchProductsByCategoryId: (
      categoryId,
      page,
      per_page = Constants.pagingLimit,
      filters = {}
    ) => {
      if (!netInfo.isConnected) return toast(Languages.noConnection);
      actions.fetchProductsByCategoryId(
        dispatch,
        categoryId,
        per_page,
        page,
        filters
      );
    },
    clearProducts: () => dispatch(actions.clearProducts()),
    addWishListItem: (product) => {
      WishListRedux.actions.addWishListItem(dispatch, product, null);
    },
    removeWishListItem: (product, variation) => {
      WishListRedux.actions.removeWishListItem(dispatch, product, null);
    },
  };
}

export default connect(
  mapStateToProps,
  undefined,
  mergeProps
)(withTheme(CategoryScreen));
