<template>
    <ul class="pagination" v-if="pages.length > 0">
        <li>
            <button class="first" type="button" @click="onClickFirstPage" :disabled="isInFirstPage">
                First
            </button>
        </li>

        <li>
            <button type="button" @click="onClickPreviousPage" :disabled="isInFirstPage">
                Previous
            </button>
        </li>

        <!-- Visible Buttons Start -->

        <li v-for="page in pages" :key="page.name">
            <button type="button" @click="onClickPage(page.name)" :disabled="page.isDisabled">
                {{ page.name }}
            </button>
        </li>

        <!-- Visible Buttons End -->

        <li>
            <button type="button" @click="onClickNextPage" :disabled="isInLastPage">
                Next
            </button>
        </li>

        <li>
            <button type="button" @click="onClickLastPage" :disabled="isInLastPage">
                Last
            </button>
        </li>
    </ul>
</template>

<script>
export default {
    name: 'Pagination',
    props: ['totalPages', 'total', 'perPage', 'currentPage'],

    data() {
        return {
            maxVisibleButtons: 10
        }
    },
    computed: {
        smallScreen() {
            if (window.size < 480) {
                this.maxVisibleButtons = 5;
                alert("small screen");
                return true;
            }
        },
        startPage() {
            if (this.currentPage === 1) {
                return 1;
            }

            if (this.currentPage === this.totalPages) {
                if (this.totalPages > this.maxVisibleButtons) {
                    return this.totalPages - (this.maxVisibleButtons + 1)
                }
                else {
                    return this.totalPages - 1
                }
            }

            return this.currentPage == 1 ? 1 : this.currentPage - 1

        },
        endPage() {
            return Math.min(this.startPage + this.maxVisibleButtons - 1, this.totalPages)
        },
        pages() {
            const range = [];
            for (let i = this.startPage; i <= this.endPage; i += 1) {
                range.push({
                    name: i,
                    isDisabled: i === this.currentPage
                });
            }
            return range;
        },
        isInFirstPage() {
            return this.currentPage === 1;
        },
        isInLastPage() {
            return this.currentPage === this.totalPages;
        },

    },
    methods: {
        onClickFirstPage() {
            this.$emit('pageChanged', 1);
        },
        onClickPreviousPage() {
            this.$emit('pageChanged', this.currentPage - 1);
        },
        onClickPage(page) {
            this.$emit('pageChanged', page);
        },
        onClickNextPage() {
            this.$emit('pageChanged', this.currentPage + 1);
        },
        onClickLastPage() {
            this.$emit('pageChanged', this.totalPages);
        }
    },
    mounted() {
        window.onresize = () => {
            if (window.innerWidth < 480) {
                this.maxVisibleButtons = 5;
            }
            else {
                this.maxVisibleButtons = 10;
            }
        }
    }
}
</script>

<style type="text/css">
.pagination {
    list-style-type: none;
    float: right;
}

.pagination button {
    border: 1px solid #cbc7c7;
    border-right: none;
    border-left: none;
}

.pagination li button {
    padding: 0 10px;
}

.pagination button.first {
    border-left: 1px solid #cbc7c7;
}

.pagination button:last-child {
    border-right: 1px solid #cbc7c7;
}

.pagination-item {
    display: inline-block;
}

button.active {
    background-color: #4AAE9B;
    color: #ffffff;
}

@media screen and (max-width: 800px) {
    .pagination li button {
        padding: 0px 5px !important;
    }
}

@media screen and (max-width: 480px) {
    .pagination {
        font-size: 12px !important;
    }
}
</style>
