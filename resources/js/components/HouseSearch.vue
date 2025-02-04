<template>
    <el-container>
        <el-header>
            <h2 class="header-title">House Search</h2>
        </el-header>
        <el-main>
            <el-row type="flex" justify="center">
                <el-col :span="24" :md="20" :lg="18">
                    <el-card>
                        <el-form :model="searchForm" label-position="top">
                            <el-row :gutter="20">
                                <el-col :span="24" :sm="8">
                                    <el-form-item label="Name">
                                        <el-input 
                                            v-model="searchForm.name" 
                                            placeholder="Enter house name"
                                            clearable
                                        ></el-input>
                                    </el-form-item>
                                </el-col>
                                
                                <el-col :span="24" :sm="8">
                                    <el-form-item label="Bedrooms">
                                        <el-input-number 
                                            v-model="searchForm.bedrooms" 
                                            :min="0" 
                                            controls-position="right"
                                            placeholder="Number of bedrooms"
                                        ></el-input-number>
                                    </el-form-item>
                                </el-col>
                                
                                <el-col :span="24" :sm="8">
                                    <el-form-item label="Bathrooms">
                                        <el-input-number 
                                            v-model="searchForm.bathrooms" 
                                            :min="0"
                                            controls-position="right"
                                            placeholder="Number of bathrooms"
                                        ></el-input-number>
                                    </el-form-item>
                                </el-col>
                            </el-row>

                            <el-row :gutter="20">
                                <el-col :span="24" :sm="8">
                                    <el-form-item label="Storeys">
                                        <el-input-number 
                                            v-model="searchForm.storeys" 
                                            :min="0"
                                            controls-position="right"
                                            placeholder="Number of storeys"
                                        ></el-input-number>
                                    </el-form-item>
                                </el-col>
                                
                                <el-col :span="24" :sm="8">
                                    <el-form-item label="Garages">
                                        <el-input-number 
                                            v-model="searchForm.garages" 
                                            :min="0"
                                            controls-position="right"
                                            placeholder="Number of garages"
                                        ></el-input-number>
                                    </el-form-item>
                                </el-col>
                            </el-row>

                            <el-row>
                                <el-col :span="24">
                                    <el-form-item label="Price Range">
                                        <el-slider
                                            v-model="priceRange"
                                            range
                                            :min="minPrice"
                                            :max="maxPrice"
                                            :step="1000"
                                        ></el-slider>
                                    </el-form-item>
                                </el-col>
                            </el-row>

                            <el-row>
                                <el-col :span="24">
                                    <el-form-item>
                                        <el-button 
                                            type="primary" 
                                            @click="searchHouses" 
                                            :loading="loading"
                                        >
                                            Search
                                        </el-button>
                                        <el-button 
                                            @click="resetForm"
                                        >
                                            Reset
                                        </el-button>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                        </el-form>

                        <div v-loading="loading">
                            <el-empty 
                                v-if="houses.length === 0 && searched" 
                                description="No results found"
                            ></el-empty>

                            <el-table
                                v-else-if="houses.length > 0"
                                :data="houses"
                                style="width: 100%"
                                border
                            >
                                <el-table-column prop="name" label="Name" sortable></el-table-column>
                                <el-table-column prop="price" label="Price" sortable>
                                    <template #default="scope">
                                        ${{ formatPrice(scope.row.price) }}
                                    </template>
                                </el-table-column>
                                <el-table-column prop="bedrooms" label="Bedrooms" sortable></el-table-column>
                                <el-table-column prop="bathrooms" label="Bathrooms" sortable></el-table-column>
                                <el-table-column prop="storeys" label="Storeys" sortable></el-table-column>
                                <el-table-column prop="garages" label="Garages" sortable></el-table-column>
                            </el-table>
                        </div>
                    </el-card>
                </el-col>
            </el-row>
        </el-main>
    </el-container>
</template>

<script>
export default {
    name: 'HouseSearch',
    props: {
        initialPriceRange: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            searchForm: {
                name: '',
                bedrooms: null,
                bathrooms: null,
                storeys: null,
                garages: null,
            },
            priceRange: [this.initialPriceRange.min, this.initialPriceRange.max],
            minPrice: this.initialPriceRange.min,
            maxPrice: this.initialPriceRange.max,
            houses: [],
            loading: false,
            searched: false
        }
    },
    methods: {
        async searchHouses() {
            this.loading = true;
            this.searched = true;
            
            try {
                const params = {
                    ...Object.fromEntries(
                        Object.entries(this.searchForm)
                            .filter(([_, value]) => value !== null && value !== '' && value !== 0)
                    ),
                    price_from: this.priceRange[0],
                    price_to: this.priceRange[1]
                };
                
                if (params.price_from === this.minPrice) {
                    delete params.price_from;
                }
                
                if (params.price_to === this.maxPrice) {
                    delete params.price_to;
                }
                
                const response = await axios.get('/api/houses/search', { params });
                this.houses = response.data;
            } catch (error) {
                this.$message.error('Error occurred while searching');
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
        resetForm() {
            this.searchForm = {
                name: '',
                bedrooms: null,
                bathrooms: null,
                storeys: null,
                garages: null
            };
            this.priceRange = [this.minPrice, this.maxPrice];
            this.houses = [];
            this.searched = false;
        },
        formatPrice(price) {
            return new Intl.NumberFormat('ru-RU').format(price);
        }
    }
}
</script>

<style>
.el-header {
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
}

.header-title {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: #303133;
}

.el-main {
    padding: 20px;
    background-color: #f5f7fa;
    min-height: calc(100vh - 60px);
}

.el-input-number {
    width: 100%;
}

.el-card {
    margin-bottom: 20px;
}

.el-form-item {
    margin-bottom: 18px;
}

.el-slider {
    margin: 10px 0;
}

@media (max-width: 768px) {
    .el-form-item {
        margin-bottom: 12px;
    }
}
</style> 