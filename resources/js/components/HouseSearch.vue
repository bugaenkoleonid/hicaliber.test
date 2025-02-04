<template>
    <el-container>
        <el-main>
            <el-card class="search-card">
                <h2>Поиск домов</h2>
                
                <el-form :model="searchForm" @submit.prevent="searchHouses">
                    <el-row :gutter="20">
                        <el-col :span="8">
                            <el-form-item label="Название">
                                <el-input v-model="searchForm.name" placeholder="Введите название"></el-input>
                            </el-form-item>
                        </el-col>
                        
                        <el-col :span="8">
                            <el-form-item label="Спальни">
                                <el-input-number v-model="searchForm.bedrooms" :min="0"></el-input-number>
                            </el-form-item>
                        </el-col>
                        
                        <el-col :span="8">
                            <el-form-item label="Ванные">
                                <el-input-number v-model="searchForm.bathrooms" :min="0"></el-input-number>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <el-row :gutter="20">
                        <el-col :span="8">
                            <el-form-item label="Этажи">
                                <el-input-number v-model="searchForm.storeys" :min="0"></el-input-number>
                            </el-form-item>
                        </el-col>
                        
                        <el-col :span="8">
                            <el-form-item label="Гаражи">
                                <el-input-number v-model="searchForm.garages" :min="0"></el-input-number>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <el-row :gutter="20">
                        <el-col :span="12">
                            <el-form-item label="Цена от">
                                <el-input-number v-model="searchForm.price_from" :min="0" :step="1000"></el-input-number>
                            </el-form-item>
                        </el-col>
                        
                        <el-col :span="12">
                            <el-form-item label="Цена до">
                                <el-input-number v-model="searchForm.price_to" :min="0" :step="1000"></el-input-number>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <el-form-item>
                        <el-button type="primary" @click="searchHouses" :loading="loading">Поиск</el-button>
                        <el-button @click="resetForm">Сбросить</el-button>
                    </el-form-item>
                </el-form>

                <div v-if="loading" class="loading-indicator">
                    <el-loading-component></el-loading-component>
                </div>

                <div v-else-if="houses.length === 0 && searched" class="no-results">
                    <el-alert
                        title="Ничего не найдено"
                        type="info"
                        :closable="false">
                    </el-alert>
                </div>

                <el-table
                    v-else-if="houses.length > 0"
                    :data="houses"
                    style="width: 100%">
                    <el-table-column prop="name" label="Название"></el-table-column>
                    <el-table-column prop="price" label="Цена">
                        <template #default="scope">
                            ${{ scope.row.price.toLocaleString() }}
                        </template>
                    </el-table-column>
                    <el-table-column prop="bedrooms" label="Спальни"></el-table-column>
                    <el-table-column prop="bathrooms" label="Ванные"></el-table-column>
                    <el-table-column prop="storeys" label="Этажи"></el-table-column>
                    <el-table-column prop="garages" label="Гаражи"></el-table-column>
                </el-table>
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
                price_from: null,
                price_to: null
            },
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
                const params = Object.fromEntries(
                    Object.entries(this.searchForm)
                        .filter(([_, value]) => value !== null && value !== '')
                );
                
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
                garages: null,
                price_from: null,
                price_to: null
            };
            this.houses = [];
            this.searched = false;
        }
    }
}
</script>

<style scoped>
.search-card {
    max-width: 1200px;
    margin: 20px auto;
}
.loading-indicator {
    text-align: center;
    margin: 20px 0;
}
.no-results {
    margin: 20px 0;
}
</style> 