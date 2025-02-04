<template>
    <el-container>
        <el-main>
            <el-card class="search-card">
                <template #header>
                    <h2>Поиск домов</h2>
                </template>
                
                <el-form :model="searchForm" label-position="top">
                    <el-row :gutter="20">
                        <el-col :span="8">
                            <el-form-item label="Название">
                                <el-input 
                                    v-model="searchForm.name" 
                                    placeholder="Введите название дома"
                                    clearable
                                ></el-input>
                            </el-form-item>
                        </el-col>
                        
                        <el-col :span="8">
                            <el-form-item label="Спальни">
                                <el-input-number 
                                    v-model="searchForm.bedrooms" 
                                    :min="0" 
                                    controls-position="right"
                                    placeholder="Количество спален"
                                ></el-input-number>
                            </el-form-item>
                        </el-col>
                        
                        <el-col :span="8">
                            <el-form-item label="Ванные">
                                <el-input-number 
                                    v-model="searchForm.bathrooms" 
                                    :min="0"
                                    controls-position="right"
                                    placeholder="Количество ванных"
                                ></el-input-number>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <el-row :gutter="20">
                        <el-col :span="8">
                            <el-form-item label="Этажи">
                                <el-input-number 
                                    v-model="searchForm.storeys" 
                                    :min="0"
                                    controls-position="right"
                                    placeholder="Количество этажей"
                                ></el-input-number>
                            </el-form-item>
                        </el-col>
                        
                        <el-col :span="8">
                            <el-form-item label="Гаражи">
                                <el-input-number 
                                    v-model="searchForm.garages" 
                                    :min="0"
                                    controls-position="right"
                                    placeholder="Количество гаражей"
                                ></el-input-number>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <el-row :gutter="20">
                        <el-col :span="24">
                            <el-form-item label="Диапазон цен">
                                <el-slider
                                    v-model="priceRange"
                                    range
                                    :min="0"
                                    :max="1000000"
                                    :step="1000"
                                    show-stops
                                ></el-slider>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <el-form-item>
                        <el-button 
                            type="primary" 
                            @click="searchHouses" 
                            :loading="loading"
                            icon="el-icon-search"
                        >
                            Поиск
                        </el-button>
                        <el-button 
                            @click="resetForm"
                            icon="el-icon-refresh"
                        >
                            Сбросить
                        </el-button>
                    </el-form-item>
                </el-form>

                <div v-loading="loading" element-loading-text="Поиск домов...">
                    <el-empty 
                        v-if="houses.length === 0 && searched" 
                        description="Ничего не найдено"
                    ></el-empty>

                    <el-table
                        v-else-if="houses.length > 0"
                        :data="houses"
                        stripe
                        border
                        style="width: 100%"
                    >
                        <el-table-column prop="name" label="Название" sortable></el-table-column>
                        <el-table-column prop="price" label="Цена" sortable>
                            <template #default="scope">
                                ${{ formatPrice(scope.row.price) }}
                            </template>
                        </el-table-column>
                        <el-table-column prop="bedrooms" label="Спальни" sortable></el-table-column>
                        <el-table-column prop="bathrooms" label="Ванные" sortable></el-table-column>
                        <el-table-column prop="storeys" label="Этажи" sortable></el-table-column>
                        <el-table-column prop="garages" label="Гаражи" sortable></el-table-column>
                    </el-table>
                </div>
            </el-card>
        </el-main>
    </el-container>
</template>

<script>
export default {
    data() {
        return {
            searchForm: {
                name: '',
                bedrooms: null,
                bathrooms: null,
                storeys: null,
                garages: null,
            },
            priceRange: [0, 1000000],
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
                            .filter(([_, value]) => value !== null && value !== '')
                    ),
                    price_from: this.priceRange[0],
                    price_to: this.priceRange[1]
                };
                
                const response = await axios.get('/api/houses/search', { params });
                this.houses = response.data;
            } catch (error) {
                this.$message.error('Произошла ошибка при поиске');
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
            this.priceRange = [0, 1000000];
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
.search-card {
    max-width: 1200px;
    margin: 20px auto;
    box-shadow: none;
}
.el-input-number {
    width: 100%;
}
.el-form-item {
    margin-bottom: 22px;
}
</style> 