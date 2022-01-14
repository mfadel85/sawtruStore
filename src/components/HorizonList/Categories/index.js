/** @format */

import React, { PureComponent } from "react";
import { FlatList } from "react-native";
import { withTheme } from "@common";
import Item from "./Item";

class Categories extends PureComponent {
  static defaultProps = {
    categories: [],
    items: [],
  };

  render() {
    const { categories, items, type, onPress, config } = this.props;
    const mapping = {};
    categories.forEach((item) => {
      mapping[item.id] = item.name;
    });

    const column = typeof config.column != 'undefined' ? config.column : 1

    return (
      <FlatList
        keyExtractor={(item, index) => `${index}`}
        contentContainerStyle={styles.flatlist}
        showsHorizontalScrollIndicator={false}

        horizontal={column == 1}
        numColumns={column}

        data={items}
        renderItem={({ item }) => (
          <Item item={item} type={type} label={mapping[item.category]} onPress={onPress} />
        )}
      />
    );
  }
}

const styles = {
  flatlist: {
    marginBottom: 10,
  }
}

export default withTheme(Categories);
