/** @format */

import React from "react";
import {
    FlatList,
    View,
    Dimensions,
    Platform
} from "react-native";
const {height} = Dimensions.get("window")
import Item from './Item'
import Pagination from './Pagination'
import {Constants} from '@common'

class SlideItem extends React.PureComponent {
    state = {
        selectedIndex: 0
    }

    renderItem = ({item, index}) => {
        const {onViewPost} = this.props
        return <Item item={item} onPress={onViewPost} active={index == this.state.selectedIndex}/>
    }

    render() {
        const {items} = this.props

        return (
            <View style={styles.container}>
                <View style={styles.content}>
                    <FlatList
                        ref="list"
                        extraData={this.state}
                        keyExtractor={(item, index) => `${index}`}
                        data={items}
                        renderItem={this.renderItem}
                        horizontal={true}
                        pagingEnabled={true}
                        showsHorizontalScrollIndicator={false}
                        onMomentumScrollEnd={this.onScrollEnd}/>

                    <Pagination
                        items={items}
                        selectedIndex={this.state.selectedIndex}
                        onNext={this.onNext}/>
                </View>

            </View>
        );
    }

    onNext = () => {
        const nextIndex = this.state.selectedIndex < this.props.items.length - 1
            ? this.state.selectedIndex + 1
            : 0
        this.onScrollToIndex(nextIndex)
    }

    onScrollToIndex = (index) => {
        this
            .refs
            .list
            .scrollToIndex({index})
        this.setState({selectedIndex: index})
    }

    onScrollEnd = (e) => {
        let contentOffset = e.nativeEvent.contentOffset;
        let viewSize = e.nativeEvent.layoutMeasurement;
        let pageNum = Math.floor(contentOffset.x / viewSize.width);

        if (pageNum != this.state.selectedIndex) {
            this.onScrollToIndex(pageNum)
        }
    }
}

const styles = {
    container: {
        margin: 10,
        borderRadius: 3,
        backgroundColor: "#fff",
    },
    content: {
        overflow: 'hidden',
        borderRadius: 6
    },
    item: {
        height: height * 0.8
    },
    viewFull: {
        height: 50,
        backgroundColor: "#fff",
        borderBottomLeftRadius: 6,
        borderBottomRightRadius: 6,
        alignItems: 'center',
        justifyContent: 'center'
    },
    fullText: {
        fontSize: 15,
        fontWeight: '600',
        color: "blue"
    }
};

export default SlideItem
