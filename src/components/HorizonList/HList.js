/** @format */

import React, { PureComponent } from "react";
import PropTypes from "prop-types";
import { FlatList, View,Text } from "react-native";
import { Constants, Images, Config, Languages, withTheme, AppConfig } from "@common";
import { HorizonLayout, AdMob, BannerSlider, BannerImage, PostList } from "@components";
import { find } from "lodash";
import styles from "./styles";
import Categories from "./Categories";
import HHeader from "./HHeader";

class HorizonList extends PureComponent {
  static propTypes = {
    config: PropTypes.object,
    index: PropTypes.number,
    fetchPost: PropTypes.func,
    onShowAll: PropTypes.func,
    list: PropTypes.array,
    fetchProductsByCollections: PropTypes.func,
    setSelectedCategory: PropTypes.func,
    onViewProductScreen: PropTypes.func,
    showCategoriesScreen: PropTypes.func,
    collection: PropTypes.object,
  };

  constructor(props) {
    super(props);

    this.page = 1;
    this.limit = Constants.pagingLimit;
    this.defaultList = [
      {
        id: 1,
        name: Languages.loading,
        images: [Images.PlaceHolder],
      },
      {
        id: 2,
        name: Languages.loading,
        images: [Images.PlaceHolder],
      },
      {
        id: 3,
        name: Languages.loading,
        images: [Images.PlaceHolder],
      },
    ];
  }

  /**
   * handle load more
   */
  _nextPosts = () => {
    const { config, index, fetchPost, collection } = this.props;
    this.page += 1;
    if (!collection.finish) {
      fetchPost({ config, index, page: this.page });
    }
  };

  _viewAll = () => {
    const {
      config,
      onShowAll,
      index,
      list,
      fetchProductsByCollections,
      setSelectedCategory,
    } = this.props;
    const selectedCategory = find(
      list,
      (category) => category.id === config.category
    );
    setSelectedCategory(selectedCategory);
    fetchProductsByCollections(config.category, config.tag, this.page, index);
    onShowAll(config, index);
  };

  showProductsByCategory = (config) => {
    const {
      onShowAll,
      index,
      list,
      fetchProductsByCollections,
      setSelectedCategory,
    } = this.props;
    const selectedCategory = find(
      list,
      (category) => category.id === config.category
    );
    setSelectedCategory(selectedCategory);
    fetchProductsByCollections(config.category, config.tag, this.page, index);
    onShowAll(config, index);
  };

  onViewProductScreen = (product) => {
    this.props.onViewProductScreen({ product });
  };

  renderItem = ({ item, index }) => {
    const { layout } = this.props.config;

    if (item === null) return <View key="post_" />;

    return (
      <HorizonLayout
        product={item}
        key={`post-${index}`}
        onViewPost={this.onViewProductScreen}
        layout={layout}
      />
    );
  };

  renderHeader = () => {
    const { showCategoriesScreen, config, theme } = this.props;
    return (
      <HHeader
        showCategoriesScreen={showCategoriesScreen}
        config={config}
        theme={theme}
        viewAll={this._viewAll}
      />
    );
  };

  render() {
    const {
      onViewProductScreen,
      collection,
      config,
      isLast,
      theme: {
        colors: { text },
      },
    } = this.props;
    //console.log('this.props',this.props);
    const {VerticalLayout} = AppConfig

    const list =
      typeof collection !== "undefined" ? collection.list : this.defaultList;
    const isPaging = !!config.paging;
    console.log('config.layoutoo',config.layout);

    switch (config.layout) {
      case Constants.Layout.circleCategory:
        return (
          <Categories 
            config={config}
            categories={this.props.list}
            items={Config.HomeCategories}
            type={config.theme}
            onPress={this.showProductsByCategory}
          />
        );
      case Constants.Layout.BannerSlider:
        return (
          <BannerSlider data={list} onViewPost={this.onViewProductScreen} />
        );

      case Constants.Layout.BannerImage:
        return (
          <BannerImage
            viewAll={this._viewAll}
            config={config}
            data={list}
            onViewPost={this.onViewProductScreen}
          />
        );
    }

    return (
      <View
        style={[
          styles.flatWrap,
          config.color && {
            backgroundColor: config.color,
          },
        ]}>
        {config.name && this.renderHeader()}
                  {/* horizontal */}

        <FlatList
          overScrollMode="never"
          contentContainerStyle={styles.flatlist}
          data={list}
          keyExtractor={(item, index) => `post__${index}`}
          renderItem={this.renderItem}
          showsHorizontalScrollIndicator={false}
          pagingEnabled={isPaging}
          onEndReached={false && this._nextPosts}
        />
          {/* horizontal */}

        {isLast && (
          <FlatList
            overScrollMode="never"
            contentContainerStyle={styles.flatlist}
            data={[1, 2, 3, 4, 5]}
            keyExtractor={(item, index) => `post__${index}`}
            renderItem={() => <AdMob adSize="largeBanner" />}
            showsHorizontalScrollIndicator={false}
          pagingEnabled={isPaging}
          />
        )}
        {/*<Text > ki o kadar ki!!</Text>*/}

        {typeof VerticalLayout != 'undefined' && 
          <PostList  
              parentLayout={VerticalLayout.layout}
              headerLabel={VerticalLayout.name}  
              onViewProductScreen={onViewProductScreen} />
        }
      </View>
    );
  }
}

export default withTheme(HorizonList);
